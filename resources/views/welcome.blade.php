<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <title>KivuBoat · Croisières sur le Lac Kivu</title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <style>
        :root {
            --primary: #0ea5e9;
            --primary-dark: #0284c7;
            --secondary: #6366f1;
            --accent: #f59e0b;
            --gradient-hero: linear-gradient(135deg, rgba(15, 23, 42, 0.88) 0%, rgba(30, 58, 138, 0.78) 50%, rgba(14, 165, 233, 0.65) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            overflow-x: hidden;
        }
        body.dark {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }

        /* ===== MENU MOBILE ===== */
        .menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0);
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 85%;
            max-width: 380px;
            height: 100vh;
            height: 100dvh;
            background: #ffffff;
            z-index: 999;
            transform: translateX(-100%) scale(0.95);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 0 0 0 rgba(0, 0, 0, 0);
            display: flex;
            flex-direction: column;
        }
        body.dark .mobile-menu {
            background: #0f172a;
        }
        .mobile-menu.active {
            transform: translateX(0) scale(1);
            opacity: 1;
            box-shadow: 20px 0 60px rgba(0, 0, 0, 0.3);
        }
        .mobile-menu::-webkit-scrollbar {
            width: 6px;
        }
        .mobile-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        .mobile-menu::-webkit-scrollbar-thumb {
            background: rgba(14, 165, 233, 0.3);
            border-radius: 10px;
        }

        .mobile-menu-header {
            position: relative;
            padding: 2.5rem 2rem 2rem;
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 50%, #8b5cf6 100%);
            color: white;
            overflow: hidden;
            min-height: 220px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }
        .mobile-menu-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.25;
            transform: scale(1.1);
            transition: transform 8s ease;
        }
        .mobile-menu.active .mobile-menu-header::before {
            transform: scale(1.2);
        }
        .mobile-menu-header::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom,
                    rgba(14, 165, 233, 0.4) 0%,
                    rgba(99, 102, 241, 0.7) 50%,
                    rgba(139, 92, 246, 0.9) 100%);
        }
        .menu-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 1;
            pointer-events: none;
        }
        .menu-particle {
            position: absolute;
            width: 6px;
            height: 6px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            animation: floatParticle 6s infinite ease-in-out;
        }
        .menu-particle:nth-child(1) {
            left: 10%;
            top: 20%;
            animation-delay: 0s;
        }
        .menu-particle:nth-child(2) {
            left: 70%;
            top: 40%;
            animation-delay: 1s;
            width: 4px;
            height: 4px;
        }
        .menu-particle:nth-child(3) {
            left: 30%;
            top: 60%;
            animation-delay: 2s;
            width: 8px;
            height: 8px;
        }
        .menu-particle:nth-child(4) {
            left: 85%;
            top: 15%;
            animation-delay: 3s;
        }
        .menu-particle:nth-child(5) {
            left: 50%;
            top: 80%;
            animation-delay: 4s;
            width: 5px;
            height: 5px;
        }
        @keyframes floatParticle {
            0%,
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0.4;
            }
            50% {
                transform: translate(20px, -30px) scale(1.5);
                opacity: 0.8;
            }
        }
        .menu-header-content {
            position: relative;
            z-index: 2;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1) 0.2s;
        }
        .mobile-menu.active .menu-header-content {
            transform: translateY(0);
            opacity: 1;
        }
        .menu-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .menu-logo-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            transform: rotate(-10deg);
            transition: transform 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .mobile-menu.active .menu-logo-icon {
            transform: rotate(0deg);
        }
        .menu-logo-text h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }
        .menu-logo-text p {
            font-size: 0.875rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .menu-location-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            margin-top: 0.75rem;
        }
        .mobile-menu-close {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            z-index: 10;
        }
        .mobile-menu-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(180deg) scale(1.1);
        }
        .mobile-menu-close i {
            font-size: 1.1rem;
            transition: transform 0.4s;
        }

        .mobile-menu-body {
            flex: 1;
            padding: 1.5rem 0;
            overflow-y: auto;
        }
        .menu-section-label {
            padding: 0.5rem 2rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        body.dark .menu-section-label {
            color: #64748b;
        }
        .menu-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, rgba(148, 163, 184, 0.3), transparent);
        }
        .mobile-menu-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 2rem;
            color: #334155;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid transparent;
            overflow: hidden;
            transform: translateX(-30px);
            opacity: 0;
        }
        body.dark .mobile-menu-link {
            color: #e2e8f0;
        }
        .mobile-menu.active .mobile-menu-link {
            transform: translateX(0);
            opacity: 1;
        }
        .mobile-menu.active .mobile-menu-link:nth-child(1) {
            transition-delay: 0.15s;
        }
        .mobile-menu.active .mobile-menu-link:nth-child(2) {
            transition-delay: 0.2s;
        }
        .mobile-menu.active .mobile-menu-link:nth-child(3) {
            transition-delay: 0.25s;
        }
        .mobile-menu.active .mobile-menu-link:nth-child(4) {
            transition-delay: 0.3s;
        }
        .mobile-menu.active .mobile-menu-link:nth-child(5) {
            transition-delay: 0.35s;
        }
        .mobile-menu.active .mobile-menu-link:nth-child(6) {
            transition-delay: 0.4s;
        }
        .mobile-menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    rgba(14, 165, 233, 0.08) 0%,
                    rgba(99, 102, 241, 0.05) 100%);
            transform: translateX(-100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: -1;
        }
        body.dark .mobile-menu-link::before {
            background: linear-gradient(90deg,
                    rgba(14, 165, 233, 0.15) 0%,
                    rgba(99, 102, 241, 0.1) 100%);
        }
        .mobile-menu-link:hover::before {
            transform: translateX(0);
        }
        .mobile-menu-link:hover {
            border-left-color: #0ea5e9;
            padding-left: 2.5rem;
            color: #0ea5e9;
        }
        body.dark .mobile-menu-link:hover {
            color: #38bdf8;
            border-left-color: #38bdf8;
        }
        .menu-link-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(99, 102, 241, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0ea5e9;
            font-size: 1rem;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            flex-shrink: 0;
        }
        body.dark .menu-link-icon {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(99, 102, 241, 0.15));
            color: #38bdf8;
        }
        .mobile-menu-link:hover .menu-link-icon {
            transform: scale(1.15) rotate(-8deg);
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }
        .menu-link-text {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .menu-link-title {
            font-weight: 600;
            font-size: 0.95rem;
        }
        .menu-link-subtitle {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 400;
            margin-top: 2px;
        }
        body.dark .menu-link-subtitle {
            color: #64748b;
        }
        .menu-link-arrow {
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
            color: #0ea5e9;
        }
        .mobile-menu-link:hover .menu-link-arrow {
            opacity: 1;
            transform: translateX(0);
        }
        .menu-divider {
            height: 1px;
            margin: 1rem 2rem;
            background: linear-gradient(to right, transparent, rgba(148, 163, 184, 0.3), transparent);
        }
        body.dark .menu-divider {
            background: linear-gradient(to right, transparent, rgba(100, 116, 139, 0.3), transparent);
        }
        .menu-cta-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin: 1rem 2rem;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            color: white;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.3);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
            transform: translateY(20px);
            opacity: 0;
        }
        .mobile-menu.active .menu-cta-button {
            transform: translateY(0);
            opacity: 1;
            transition-delay: 0.45s;
        }
        .menu-cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }
        .menu-cta-button:hover::before {
            left: 100%;
        }
        .menu-cta-button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 32px rgba(14, 165, 233, 0.5);
        }
        .mobile-menu-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(148, 163, 184, 0.15);
            background: linear-gradient(to top, rgba(241, 245, 249, 0.5), transparent);
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.5s ease 0.5s;
        }
        body.dark .mobile-menu-footer {
            background: linear-gradient(to top, rgba(15, 23, 42, 0.5), transparent);
            border-top-color: rgba(100, 116, 139, 0.15);
        }
        .mobile-menu.active .mobile-menu-footer {
            transform: translateY(0);
            opacity: 1;
        }
        .menu-footer-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
            margin-bottom: 1rem;
        }
        body.dark .menu-footer-title {
            color: #64748b;
        }
        .menu-social-links {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .menu-social-link {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1), rgba(99, 102, 241, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0ea5e9;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            font-size: 0.95rem;
        }
        body.dark .menu-social-link {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(99, 102, 241, 0.15));
            color: #38bdf8;
        }
        .menu-social-link:hover {
            transform: translateY(-4px) scale(1.1);
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white;
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.4);
        }
        .menu-footer-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #94a3b8;
        }
        body.dark .menu-footer-info {
            color: #64748b;
        }
        .menu-footer-info i {
            color: #10b981;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(14, 165, 233, 0.1);
            border: none;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1000;
        }
        .hamburger:hover {
            background: rgba(14, 165, 233, 0.2);
            transform: scale(1.05);
        }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2.5px;
            background: linear-gradient(90deg, #0ea5e9, #6366f1);
            border-radius: 3px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform-origin: center;
        }
        body.dark .hamburger {
            background: rgba(56, 189, 248, 0.15);
        }
        body.dark .hamburger span {
            background: linear-gradient(90deg, #38bdf8, #818cf8);
        }
        .hamburger.active span:nth-child(1) {
            transform: translateY(7.5px) rotate(45deg);
        }
        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger.active span:nth-child(3) {
            transform: translateY(-7.5px) rotate(-45deg);
        }
        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }
        }

        /* ===== COMPOSANTS ===== */
        .nav-premium {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
        }
        body.dark .nav-premium {
            background: rgba(15, 23, 42, 0.95);
            border-bottom-color: rgba(255, 255, 255, 0.05);
        }

        .hero-carousel {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .hero-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 2s cubic-bezier(0.4, 0, 0.2, 1);
            transform: scale(1.1);
        }
        .hero-slide.active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 2s ease, transform 6s ease;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: var(--gradient-hero);
        }
        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            max-width: 1200px;
            animation: fadeInUp 1.2s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .badge-float {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            animation: float 3s ease-in-out infinite;
            margin-bottom: 2rem;
        }
        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .hero-title {
            font-size: clamp(2.5rem, 8vw, 5rem);
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #fff 0%, #bae6fd 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .carousel-dots {
            position: absolute;
            bottom: 3rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 1rem;
            z-index: 20;
        }
        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .carousel-dot.active {
            background: #38bdf8;
            transform: scale(1.3);
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.6);
        }
        .carousel-dot:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.05);
        }
        body.dark .glass-card {
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(255, 255, 255, 0.05);
        }
        .glass-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        .icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            color: white;
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.3);
            transition: transform 0.6s;
        }
        .glass-card:hover .icon-wrapper {
            transform: rotateY(360deg);
        }

        /* Bateaux */
        .boat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }
        .boat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }
        body.dark .boat-card {
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(255, 255, 255, 0.05);
        }
        .boat-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }
        .boat-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .boat-card .boat-info {
            padding: 1.5rem;
        }
        .boat-card .boat-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
        }
        body.dark .boat-card .boat-name {
            color: #f1f5f9;
        }
        .boat-card .boat-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        .boat-card .boat-features span {
            background: rgba(14, 165, 233, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.8rem;
            color: #0ea5e9;
            font-weight: 500;
        }
        body.dark .boat-card .boat-features span {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
        }
        .boat-price {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0ea5e9;
        }
        body.dark .boat-price {
            color: #38bdf8;
        }

        .stats-section {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            position: relative;
            overflow: hidden;
        }
        .stat-item {
            text-align: center;
            color: white;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s;
        }
        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .testimonial-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            padding: 2rem;
            transition: all 0.4s ease;
        }
        body.dark .testimonial-card {
            background: rgba(30, 41, 59, 0.6);
            border-color: rgba(255, 255, 255, 0.05);
        }
        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
        }
        .testimonial-stars {
            color: #f59e0b;
        }

        .map-container {
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
        }
        .map-container:hover {
            transform: scale(1.01);
            box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
        }

        .weather-card {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
            border: 1px solid rgba(14, 165, 233, 0.2);
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s;
        }
        .weather-card:hover {
            transform: translateY(-5px);
        }
        body.dark .weather-card {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(99, 102, 241, 0.05) 100%);
        }

        .scroll-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            color: white;
            border: none;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(14, 165, 233, 0.4);
            z-index: 50;
        }
        .scroll-top.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .scroll-top:hover {
            transform: translateY(-4px) scale(1.1);
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* CTA réservation */
        .cta-reservation {
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 8px 24px rgba(14, 165, 233, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .cta-reservation:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 36px rgba(14, 165, 233, 0.5);
        }

        .btn-outline-light {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: white;
        }

        @media (max-width: 768px) {
            .boat-grid {
                grid-template-columns: 1fr;
            }
            .hero-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- ===== OVERLAY & MENU MOBILE ===== -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="menu-particles">
                <div class="menu-particle"></div>
                <div class="menu-particle"></div>
                <div class="menu-particle"></div>
                <div class="menu-particle"></div>
                <div class="menu-particle"></div>
            </div>
            <button class="mobile-menu-close" id="mobileMenuClose" aria-label="Fermer le menu">
                <i class="fas fa-times"></i>
            </button>
            <div class="menu-header-content">
                <div class="menu-logo">
                    <div class="menu-logo-icon">
                        <i class="fas fa-ship"></i>
                    </div>
                    <div class="menu-logo-text">
                        <h2>KivuBoat</h2>
                        <p><i class="fas fa-anchor"></i> Croisières & Excursions</p>
                    </div>
                </div>
                <div class="menu-location-badge">
                    <i class="fas fa-map-marker-alt"></i> Lac Kivu · Goma, RDC
                </div>
            </div>
        </div>

        <div class="mobile-menu-body">
            <div class="menu-section-label">Navigation</div>
            <a href="#accueil" class="mobile-menu-link" onclick="closeMobileMenu()">
                <div class="menu-link-icon"><i class="fas fa-home"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Accueil</span><span class="menu-link-subtitle">Présentation</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>
            <a href="#bateaux" class="mobile-menu-link" onclick="closeMobileMenu()">
                <div class="menu-link-icon"><i class="fas fa-ship"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Bateaux</span><span class="menu-link-subtitle">Flotte disponible</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>
            <a href="#excursions" class="mobile-menu-link" onclick="closeMobileMenu()">
                <div class="menu-link-icon"><i class="fas fa-compass"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Excursions</span><span class="menu-link-subtitle">Itinéraires proposés</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>
            <a href="#temoignages" class="mobile-menu-link" onclick="closeMobileMenu()">
                <div class="menu-link-icon"><i class="fas fa-star"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Avis</span><span class="menu-link-subtitle">Témoignages clients</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>
            <!-- Le lien "Réservation" pointe vers login -->
            <a href="{{ route('login') }}" class="mobile-menu-link" onclick="closeMobileMenu()">
                <div class="menu-link-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Réservation</span><span class="menu-link-subtitle">Connectez-vous pour réserver</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>
            <a href="#contact" class="mobile-menu-link" onclick="closeMobileMenu()">
                <div class="menu-link-icon"><i class="fas fa-envelope"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Contact</span><span class="menu-link-subtitle">Nous joindre</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>

            <div class="menu-divider"></div>
            <div class="menu-section-label">Mon compte</div>
            <a href="{{ route('login') }}" class="mobile-menu-link">
                <div class="menu-link-icon"><i class="fas fa-sign-in-alt"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Connexion</span><span class="menu-link-subtitle">Accéder à mon espace</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>
            <a href="{{ route('register') }}" class="mobile-menu-link">
                <div class="menu-link-icon"><i class="fas fa-user-plus"></i></div>
                <div class="menu-link-text"><span class="menu-link-title">Inscription</span><span class="menu-link-subtitle">Créer un compte</span></div>
                <i class="fas fa-chevron-right menu-link-arrow"></i>
            </a>

            <a href="{{ route('login') }}" class="menu-cta-button" onclick="closeMobileMenu()">
                <i class="fas fa-ship"></i> Réserver un bateau
            </a>
        </div>

        <div class="mobile-menu-footer">
            <div class="menu-footer-title">Suivez-nous</div>
            <div class="menu-social-links">
                <a href="#" class="menu-social-link"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="menu-social-link"><i class="fab fa-instagram"></i></a>
                <a href="#" class="menu-social-link"><i class="fab fa-twitter"></i></a>
                <a href="#" class="menu-social-link"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="menu-footer-info">
                <i class="fas fa-circle" style="font-size: 6px;"></i>
                <span>En ligne · Disponible 24/7</span>
            </div>
        </div>
    </div>

    <!-- ===== NAVIGATION PRINCIPALE ===== -->
    <nav class="nav-premium fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-ship"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white font-serif">KivuBoat</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Lac Kivu · Croisières</p>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="#accueil" class="text-gray-600 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition font-medium">Accueil</a>
                <a href="#bateaux" class="text-gray-600 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition font-medium">Bateaux</a>
                <a href="#excursions" class="text-gray-600 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition font-medium">Excursions</a>
                <a href="#temoignages" class="text-gray-600 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition font-medium">Avis</a>
                <!-- Lien Réservation -> Login -->
                <a href="{{ route('login') }}" class="text-gray-600 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition font-medium flex items-center gap-1">
                    <i class="fas fa-calendar-check"></i> Réservation
                </a>
                <a href="#contact" class="text-gray-600 dark:text-gray-300 hover:text-sky-600 dark:hover:text-sky-400 transition font-medium">Contact</a>
            </div>

            <div class="flex items-center gap-3">
                <button id="darkToggle" class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-moon"></i>
                </button>
                <a href="{{ route('login') }}" class="hidden sm:block px-5 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium hover:shadow-lg hover:shadow-sky-500/30 transition">
                    Connexion
                </a>
                <button class="hamburger" id="hamburger" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="hero-carousel" id="accueil">
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1920&q=80');"></div>

        <div class="hero-overlay"></div>

        <div class="hero-content">
            <div class="badge-float">
                <i class="fas fa-anchor"></i>
                <span>Embarquez pour une aventure inoubliable</span>
            </div>
            <h1 class="hero-title font-serif">
                Découvrez le Lac Kivu<br/>
                <span class="text-sky-200">en Bateau</span>
            </h1>
            <p class="hero-subtitle">
                Croisières privées, excursions familiales ou sorties romantiques au coucher du soleil.
                Réservez votre bateau et vivez une expérience unique sur les eaux paisibles du lac.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}" class="cta-reservation">
                    <i class="fas fa-calendar-check"></i> Réservez maintenant
                </a>
                <a href="#bateaux" class="btn-outline-light">
                    <i class="fas fa-ship"></i> Voir nos bateaux
                </a>
            </div>
        </div>

        <div class="carousel-dots" id="carouselDots"></div>
    </section>

    <!-- ===== STATS ===== -->
    <section class="stats-section py-16">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-item reveal">
                    <div class="stat-number" data-target="12">0</div>
                    <div class="text-sky-100 font-medium">Bateaux disponibles</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-number" data-target="8">0</div>
                    <div class="text-sky-100 font-medium">Excursions proposées</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-number" data-target="150">0</div>
                    <div class="text-sky-100 font-medium">Clients satisfaits</div>
                </div>
                <div class="stat-item reveal">
                    <div class="stat-number" data-target="4.9">0</div>
                    <div class="text-sky-100 font-medium">Note moyenne / 5</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== NOS BATEAUX ===== -->
    <section class="py-20 px-6" id="bateaux">
        <div class="container mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white font-serif mb-4">
                    Notre Flotte
                </h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg">
                    Des bateaux confortables et sécurisés pour toutes vos envies de navigation sur le Lac Kivu.
                </p>
            </div>

            <div class="boat-grid">
                <!-- Bateau 1 -->
                <div class="boat-card reveal">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80" alt="Bateau de luxe" loading="lazy" />
                    <div class="boat-info">
                        <h3 class="boat-name">Kivu Princess</h3>
                        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users"></i> <span>12 passagers</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <i class="fas fa-clock"></i> <span>3h / 6h</span>
                        </div>
                        <div class="boat-features">
                            <span><i class="fas fa-wifi"></i> Wi-Fi</span>
                            <span><i class="fas fa-music"></i> Audio</span>
                            <span><i class="fas fa-utensils"></i> Bar</span>
                            <span><i class="fas fa-life-ring"></i> Sécurité</span>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="boat-price">$180</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">/ jour</span>
                        </div>
                        <a href="{{ route('login') }}" class="mt-4 block text-center py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium hover:shadow-lg transition">
                            Réserver
                        </a>
                    </div>
                </div>

                <!-- Bateau 2 -->
                <div class="boat-card reveal" style="transition-delay: 0.1s">
                    <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?w=800&q=80" alt="Bateau de pêche" loading="lazy" />
                    <div class="boat-info">
                        <h3 class="boat-name">Ngoma Express</h3>
                        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users"></i> <span>8 passagers</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <i class="fas fa-clock"></i> <span>2h / 4h</span>
                        </div>
                        <div class="boat-features">
                            <span><i class="fas fa-fish"></i> Pêche</span>
                            <span><i class="fas fa-umbrella"></i> Toit</span>
                            <span><i class="fas fa-sun"></i> Terrasse</span>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="boat-price">$120</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">/ jour</span>
                        </div>
                        <a href="{{ route('login') }}" class="mt-4 block text-center py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium hover:shadow-lg transition">
                            Réserver
                        </a>
                    </div>
                </div>

                <!-- Bateau 3 -->
                <div class="boat-card reveal" style="transition-delay: 0.2s">
                    <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&q=80" alt="Catamaran" loading="lazy" />
                    <div class="boat-info">
                        <h3 class="boat-name">Island Dream</h3>
                        <div class="flex items-center gap-2 mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-users"></i> <span>20 passagers</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <i class="fas fa-clock"></i> <span>4h / 8h</span>
                        </div>
                        <div class="boat-features">
                            <span><i class="fas fa-swimmer"></i> Plateforme</span>
                            <span><i class="fas fa-cocktail"></i> Bar</span>
                            <span><i class="fas fa-music"></i> Son</span>
                            <span><i class="fas fa-umbrella"></i> Salon</span>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="boat-price">$250</span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">/ jour</span>
                        </div>
                        <a href="{{ route('login') }}" class="mt-4 block text-center py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-indigo-600 text-white font-medium hover:shadow-lg transition">
                            Réserver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== EXCURSIONS ===== -->
    <section class="py-20 px-6 bg-white/50 dark:bg-gray-900/50" id="excursions">
        <div class="container mx-auto">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white font-serif mb-4">
                    Nos Excursions
                </h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg">
                    Des itinéraires soigneusement sélectionnés pour découvrir les merveilles du Lac Kivu.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="glass-card reveal">
                    <div class="icon-wrapper"><i class="fas fa-sun"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">Coucher de soleil</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Embarquez en fin d'après-midi pour admirer les couleurs flamboyantes du ciel se reflétant sur le lac. Apéritif offert.
                    </p>
                    <div class="flex items-center gap-2 mt-4 text-sm text-sky-600 dark:text-sky-400">
                        <i class="fas fa-clock"></i> 3h · <i class="fas fa-users"></i> 2-12 pers.
                    </div>
                </div>

                <div class="glass-card reveal" style="transition-delay: 0.1s">
                    <div class="icon-wrapper"><i class="fas fa-mountain"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">Tour des îles</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Naviguez autour des îles du lac, découvrez la faune et la flore, et faites une escale pour une baignade.
                    </p>
                    <div class="flex items-center gap-2 mt-4 text-sm text-sky-600 dark:text-sky-400">
                        <i class="fas fa-clock"></i> 6h · <i class="fas fa-users"></i> 2-20 pers.
                    </div>
                </div>

                <div class="glass-card reveal" style="transition-delay: 0.2s">
                    <div class="icon-wrapper"><i class="fas fa-fish"></i></div>
                    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-3">Pêche traditionnelle</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                        Accompagné d'un pêcheur local, apprenez les techniques ancestrales et dégustez votre prise grillée.
                    </p>
                    <div class="flex items-center gap-2 mt-4 text-sm text-sky-600 dark:text-sky-400">
                        <i class="fas fa-clock"></i> 4h · <i class="fas fa-users"></i> 2-8 pers.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TÉMOIGNAGES ===== -->
    <section class="py-20 px-6" id="temoignages">
        <div class="container mx-auto max-w-5xl">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white font-serif mb-4">
                    Ce qu'ils disent de nous
                </h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg">
                    Des avis authentiques de voyageurs qui ont vécu l'expérience KivuBoat.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="testimonial-card reveal">
                    <div class="testimonial-stars text-lg">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mt-3 leading-relaxed">
                        "Une expérience magique ! Le coucher de soleil sur le lac est à couper le souffle. L'équipage était aux petits soins."
                    </p>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-full bg-sky-200 flex items-center justify-center text-sky-700 font-bold">SM</div>
                        <div>
                            <div class="font-semibold text-gray-800 dark:text-white">Sophie M.</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Paris, France</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card reveal" style="transition-delay: 0.1s">
                    <div class="testimonial-stars text-lg">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mt-3 leading-relaxed">
                        "Nous avons passé une journée inoubliable en famille. Les enfants ont adoré la baignade et l'ambiance était parfaite."
                    </p>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-full bg-sky-200 flex items-center justify-center text-sky-700 font-bold">JK</div>
                        <div>
                            <div class="font-semibold text-gray-800 dark:text-white">Jean K.</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Goma, RDC</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card reveal" style="transition-delay: 0.2s">
                    <div class="testimonial-stars text-lg">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mt-3 leading-relaxed">
                        "Le catamaran est spacieux et confortable. La visite des îles était magnifique, je recommande vivement !"
                    </p>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-10 h-10 rounded-full bg-sky-200 flex items-center justify-center text-sky-700 font-bold">AR</div>
                        <div>
                            <div class="font-semibold text-gray-800 dark:text-white">Anna R.</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Londres, UK</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CALL TO ACTION : CONNEXION ===== -->
    <section class="py-20 px-6 bg-gradient-to-r from-sky-600 to-indigo-700">
        <div class="container mx-auto max-w-4xl text-center text-white reveal">
            <i class="fas fa-ship text-6xl text-white/30 mb-6 block"></i>
            <h2 class="text-4xl md:text-5xl font-bold font-serif mb-4">Prêt à voguer sur le Lac Kivu ?</h2>
            <p class="text-xl text-white/80 max-w-2xl mx-auto mb-8">
                Créez votre compte ou connectez-vous pour réserver votre bateau et choisir votre excursion.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-4 rounded-full bg-white text-sky-900 font-semibold hover:shadow-2xl hover:scale-105 transition flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </a>
                <a href="{{ route('register') }}" class="px-8 py-4 rounded-full bg-white/10 backdrop-blur-md border-2 border-white/30 text-white font-semibold hover:bg-white/20 transition flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Créer un compte
                </a>
            </div>
            <p class="text-sm text-white/60 mt-6">
                <i class="fas fa-lock mr-1"></i> Vos données sont sécurisées. Connexion rapide et facile.
            </p>
        </div>
    </section>

    <!-- ===== MÉTÉO & CARTE ===== -->
    <section class="py-16 px-6">
        <div class="container mx-auto max-w-5xl">
            <div class="grid md:grid-cols-2 gap-8">
                <div class="map-container reveal">
                    <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1021512.5465658028!2d28.69367395!3d-1.84791905!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca8b6d9cf0fc3%3A0x9cd195ac05c89e9b!2sLake%20Kivu!5e0!3m2!1sen!2sus!4v1740000000000"
                    width="100%"
                    height="300"
                    style="border:0; border-radius:20px;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Carte du Lac Kivu"
                    >
                </iframe>
            </div>

            <div class="weather-card reveal">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="text-6xl">
                            <i class="fas fa-cloud-sun text-sky-500" id="weatherIcon"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-gray-800 dark:text-white font-serif" id="weatherTemp">--°C</div>
                            <div class="text-lg text-gray-600 dark:text-gray-400" id="weatherDesc">Chargement...</div>
                            <div class="text-sm text-gray-500">Goma, RDC</div>
                        </div>
                    </div>
                    <div class="flex-1 grid grid-cols-2 gap-3 w-full">
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Humidité</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-white" id="weatherHumidity">--%</div>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Vent</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-white" id="weatherWind">-- km/h</div>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Visibilité</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-white" id="weatherVisibility">-- km</div>
                        </div>
                        <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-3">
                            <div class="text-xs text-gray-500 dark:text-gray-400">Ressenti</div>
                            <div class="text-xl font-bold text-gray-800 dark:text-white" id="weatherFeelsLike">--°C</div>
                        </div>
                    </div>
                </div>
                <div id="weatherError" class="mt-4 p-3 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl hidden text-sm">
                    <i class="fas fa-exclamation-triangle mr-2"></i> Erreur de chargement de la météo.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CONTACT / FOOTER ===== -->
<footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800" id="contact">
    <div class="container mx-auto px-6">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-600 flex items-center justify-center text-white">
                        <i class="fas fa-ship"></i>
                    </div>
                    <span class="text-xl font-bold text-white font-serif">KivuBoat</span>
                </div>
                <p class="text-sm leading-relaxed">
                    Location de bateaux et excursions sur le Lac Kivu.
                    Profitez d'une expérience unique au cœur de l'Afrique.
                </p>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#accueil" class="hover:text-sky-400 transition">Accueil</a></li>
                    <li><a href="#bateaux" class="hover:text-sky-400 transition">Bateaux</a></li>
                    <li><a href="#excursions" class="hover:text-sky-400 transition">Excursions</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-sky-400 transition">Réservation</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Informations</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-sky-400 transition">Mentions légales</a></li>
                    <li><a href="#" class="hover:text-sky-400 transition">Politique de confidentialité</a></li>
                    <li><a href="#" class="hover:text-sky-400 transition">Conditions générales</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold mb-4">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li><i class="fas fa-map-marker-alt mr-2"></i> Goma, Nord-Kivu, RDC</li>
                    <li><i class="fas fa-envelope mr-2"></i> contact@kivuboat.com</li>
                    <li><i class="fas fa-phone mr-2"></i> +243 XXX XXX XXX</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm">
                <i class="far fa-copyright mr-1"></i> 2026 KivuBoat. Tous droits réservés.
            </p>
            <div class="flex gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-sky-600 transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-sky-600 transition">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-sky-600 transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-sky-600 transition">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- ===== SCROLL TO TOP ===== -->
<button class="scroll-top" id="scrollTop" aria-label="Retour en haut">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- ============================================ -->
<!-- JAVASCRIPT                                   -->
<!-- ============================================ -->
<script>
    (function() {
        'use strict';

        // ---- Menu Mobile ----
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');
        const mobileMenuClose = document.getElementById('mobileMenuClose');

        function openMobileMenu() {
            hamburger.classList.add('active');
            mobileMenu.classList.add('active');
            menuOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            hamburger.classList.remove('active');
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        window.closeMobileMenu = closeMobileMenu;

        hamburger.addEventListener('click', () => {
            mobileMenu.classList.contains('active') ? closeMobileMenu() : openMobileMenu();
        });
        mobileMenuClose.addEventListener('click', closeMobileMenu);
        menuOverlay.addEventListener('click', closeMobileMenu);
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) closeMobileMenu();
        });

        // ---- Carousel ----
        const slides = document.querySelectorAll('.hero-slide');
        const dotsContainer = document.getElementById('carouselDots');
        let currentIndex = 0;
        let interval;

        slides.forEach((_, i) => {
            const dot = document.createElement('span');
            dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        });

        const dots = dotsContainer.querySelectorAll('.carousel-dot');

        function goToSlide(index) {
            slides.forEach((s, i) => s.classList.toggle('active', i === index));
            dots.forEach((d, i) => d.classList.toggle('active', i === index));
            currentIndex = index;
        }

        function nextSlide() {
            goToSlide((currentIndex + 1) % slides.length);
        }

        function startCarousel() {
            stopCarousel();
            interval = setInterval(nextSlide, 5000);
        }

        function stopCarousel() {
            if (interval) clearInterval(interval);
        }

        document.getElementById('accueil').addEventListener('mouseenter', stopCarousel);
        document.getElementById('accueil').addEventListener('mouseleave', startCarousel);
        startCarousel();

        // ---- Dark Mode ----
        const darkToggle = document.getElementById('darkToggle');
        const body = document.body;
        const icon = darkToggle.querySelector('i');

        if (localStorage.getItem('darkMode') === 'true') {
            body.classList.add('dark');
            icon.classList.replace('fa-moon', 'fa-sun');
        }

        darkToggle.addEventListener('click', () => {
            body.classList.toggle('dark');
            const isDark = body.classList.contains('dark');
            localStorage.setItem('darkMode', isDark);
            icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        });

        // ---- Météo ----
        async function fetchWeather() {
            try {
                const res = await fetch('https://wttr.in/Goma?format=j1&lang=fr');
                const data = await res.json();
                const c = data.current_condition[0];

                document.getElementById('weatherTemp').textContent = c.temp_C + '°C';
                document.getElementById('weatherDesc').textContent = c.weatherDesc[0].value;
                document.getElementById('weatherHumidity').textContent = c.humidity + '%';
                document.getElementById('weatherWind').textContent = c.windspeedKmph + ' km/h';
                document.getElementById('weatherVisibility').textContent = c.visibility + ' km';
                document.getElementById('weatherFeelsLike').textContent = c.FeelsLikeC + '°C';

                const codes = {
                    '113': 'fa-sun',
                    '116': 'fa-cloud-sun',
                    '119': 'fa-cloud',
                    '122': 'fa-cloud-sun',
                    '176': 'fa-cloud-rain',
                    '200': 'fa-bolt',
                    '227': 'fa-snowflake',
                    '260': 'fa-smog'
                };
                document.getElementById('weatherIcon').className = 'fas ' + (codes[c.weatherCode] ||
                    'fa-cloud-sun') + ' text-sky-500';
            } catch (e) {
                document.getElementById('weatherError').classList.remove('hidden');
            }
        }
        fetchWeather();
        setInterval(fetchWeather, 600000);

        // ---- Scroll to Top ----
        const scrollTop = document.getElementById('scrollTop');
        window.addEventListener('scroll', () => {
            scrollTop.classList.toggle('visible', window.scrollY > 300);
        });
        scrollTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // ---- Reveal on Scroll ----
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    const num = entry.target.querySelector('.stat-number');
                    if (num && !num.dataset.animated) {
                        num.dataset.animated = 'true';
                        const target = parseFloat(num.dataset.target);
                        if (!isNaN(target)) {
                            let current = 0;
                            const inc = target / 50;
                            const timer = setInterval(() => {
                                current += inc;
                                if (current >= target) {
                                    num.textContent = target.toLocaleString();
                                    clearInterval(timer);
                                } else {
                                    num.textContent = Math.floor(current).toLocaleString();
                                }
                            }, 30);
                        }
                    }
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // ---- Navbar shadow ----
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            nav.classList.toggle('shadow-md', window.scrollY > 50);
        });

        console.log('⛵ KivuBoat — Page d\'accueil avec réservation protégée');
        console.log('🔐 Tous les liens "Réserver" redirigent vers la page de connexion.');
    })();
</script>

</body>
</html>