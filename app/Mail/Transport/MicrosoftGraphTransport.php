<?php

namespace App\Mail\Transport;

use GuzzleHttp\Client;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class MicrosoftGraphTransport extends AbstractTransport
{
    protected string $tenantId;
    protected string $clientId;
    protected string $clientSecret;
    protected string $fromEmail;
    protected ?string $accessToken = null;
    protected ?int $tokenExpiry = null;

    public function __construct(
        string $tenantId,
        string $clientId,
        string $clientSecret,
        string $fromEmail
    ) {
        parent::__construct();
        
        $this->tenantId = $tenantId;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->fromEmail = $fromEmail;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        
        $token = $this->getAccessToken();
        
        $client = new Client();
        
        // Build recipients
        $toRecipients = [];
        foreach ($email->getTo() as $address) {
            $toRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: $address->getAddress(),
                ]
            ];
        }
        
        $ccRecipients = [];
        foreach ($email->getCc() as $address) {
            $ccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: $address->getAddress(),
                ]
            ];
        }
        
        $bccRecipients = [];
        foreach ($email->getBcc() as $address) {
            $bccRecipients[] = [
                'emailAddress' => [
                    'address' => $address->getAddress(),
                    'name' => $address->getName() ?: $address->getAddress(),
                ]
            ];
        }
        
        // Build message payload
        $payload = [
            'message' => [
                'subject' => $email->getSubject(),
                'body' => [
                    'contentType' => $email->getHtmlBody() ? 'HTML' : 'Text',
                    'content' => $email->getHtmlBody() ?: $email->getTextBody(),
                ],
                'toRecipients' => $toRecipients,
            ],
            'saveToSentItems' => true,
        ];
        
        if (!empty($ccRecipients)) {
            $payload['message']['ccRecipients'] = $ccRecipients;
        }
        
        if (!empty($bccRecipients)) {
            $payload['message']['bccRecipients'] = $bccRecipients;
        }
        
        // Send email via Graph API
        $response = $client->post(
            "https://graph.microsoft.com/v1.0/users/{$this->fromEmail}/sendMail",
            [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]
        );
        
        if ($response->getStatusCode() !== 202) {
            throw new \Exception('Failed to send email via Microsoft Graph: ' . $response->getBody());
        }
    }

    protected function getAccessToken(): string
    {
        // Check if token is still valid
        if ($this->accessToken && $this->tokenExpiry && time() < $this->tokenExpiry) {
            return $this->accessToken;
        }
        
        // Get new token
        $client = new Client();
        
        $response = $client->post(
            "https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token",
            [
                'form_params' => [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => 'https://graph.microsoft.com/.default',
                    'grant_type' => 'client_credentials',
                ],
            ]
        );
        
        $data = json_decode($response->getBody(), true);
        
        $this->accessToken = $data['access_token'];
        $this->tokenExpiry = time() + ($data['expires_in'] - 60); // Refresh 1 minute before expiry
        
        return $this->accessToken;
    }

    public function __toString(): string
    {
        return 'microsoft+graph';
    }
}
