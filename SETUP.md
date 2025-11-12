# Hotel Booking Website - Setup Guide

## Project Overview
This is a professional hotel booking homepage built with Laravel, inspired by Almosafer and Almatar. The design features:
- Modern, responsive UI with RTL support (Arabic)
- Hero section with advanced search functionality
- Special offers and deals section
- Popular destinations showcase
- Featured hotels display
- Customer reviews section
- Professional navigation and footer

## Installation Steps

### 1. Install PHP Dependencies
```bash
composer install
```

### 2. Install Node Dependencies
```bash
npm install
```

This will install:
- Tailwind CSS
- Vite
- PostCSS
- Autoprefixer

### 3. Build Assets (Development)
```bash
npm run dev
```

### 4. Build Assets (Production)
```bash
npm run build
```

### 5. Start Laravel Development Server
```bash
php artisan serve
```

The website will be available at: `http://localhost:8000`

## Features

### Homepage Sections:
1. **Hero Section**: Prominent search bar with destination, dates, and guests selection
2. **Special Offers**: Three featured deals with discount badges
3. **Popular Destinations**: Grid of popular cities with hotel counts
4. **Featured Hotels**: Hotel cards with ratings, prices, and booking buttons
5. **Customer Reviews**: Testimonials from satisfied customers
6. **Why Choose Us**: Trust indicators and key benefits

### Design Features:
- ✅ Fully responsive (mobile, tablet, desktop)
- ✅ RTL (Right-to-Left) support for Arabic
- ✅ Modern gradient backgrounds
- ✅ Smooth animations and transitions
- ✅ Professional color scheme (blue/indigo)
- ✅ Font Awesome icons
- ✅ Google Fonts (Cairo)

## File Structure

```
resources/
├── views/
│   ├── layouts/
│   │   └── app.blade.php    # Main layout with nav & footer
│   └── home.blade.php        # Homepage content
├── css/
│   └── app.css              # Tailwind CSS + custom styles
└── js/
    └── app.js               # JavaScript for interactivity

routes/
└── web.php                  # Route to homepage
```

## Customization

### Colors
Edit `tailwind.config.js` to change the color scheme. The main colors used are:
- Blue: `blue-600`, `blue-700`
- Indigo: `indigo-800`
- Gray: Various shades for text and backgrounds

### Fonts
The project uses Cairo font from Google Fonts. To change, edit:
- `resources/views/layouts/app.blade.php` (font link)
- `tailwind.config.js` (font family)

### Images
Replace placeholder images in `home.blade.php` with your own hotel images. Current images are from Unsplash.

## Notes

- This is a **front-end only** implementation
- No backend functionality is implemented (search, booking, etc.)
- All forms are for display purposes only
- Images are placeholder URLs from Unsplash

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

This project is for demonstration purposes.

