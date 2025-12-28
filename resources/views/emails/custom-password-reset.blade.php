<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333333;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }
        
        .email-header {
            background: linear-gradient(135deg, #111111 0%, #333333 100%);
            padding: 40px 30px;
            text-align: center;
            border-radius: 16px 16px 0 0;
        }
        
        .brand-logo {
            font-size: 28px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            letter-spacing: 1px;
        }
        
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #111111;
            margin-bottom: 24px;
        }
        
        .intro-text {
            color: #666666;
            font-size: 16px;
            margin-bottom: 32px;
        }
        
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #111111 0%, #333333 100%);
            color: white;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin: 20px 0;
            box-shadow: 0 4px 20px rgba(17, 17, 17, 0.15);
        }
        
        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(17, 17, 17, 0.25);
            background: linear-gradient(135deg, #000000 0%, #222222 100%);
        }
        
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
            margin: 40px 0;
        }
        
        .url-container {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #333333;
        }
        
        .expiry-notice {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .expiry-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #856404;
            margin-bottom: 10px;
        }
        
        .expiry-text {
            color: #856404;
            font-size: 14px;
            line-height: 1.5;
        }
        
        .email-footer {
            padding: 30px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0 0 16px 16px;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }
        
        .footer-links {
            margin-top: 20px;
        }
        
        .footer-link {
            color: #111111;
            text-decoration: none;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: #333333;
            text-decoration: underline;
        }
        
        .copyright {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
        }
        
        .user-avatar {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 24px;
            font-weight: 600;
        }
        
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 12px;
            }
            
            .email-header, .email-content, .email-footer {
                padding: 25px 20px;
            }
            
            .greeting {
                font-size: 20px;
            }
            
            .reset-button {
                padding: 14px 32px;
                font-size: 15px;
            }
            
            .expiry-notice {
                padding: 16px;
            }
            
            .footer-link {
                display: block;
                margin: 10px 0;
            }
        }
        
        /* Email client specific fixes */
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            /* Safari and Chrome specific styles */
            .reset-button {
                -webkit-text-size-adjust: none;
            }
        }
        
        /* Outlook specific fixes */
        @media screen and (min-width: 600px) {
            .email-container {
                width: 600px !important;
            }
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #1a1a1a;
            }
            
            .email-container {
                background: #2d2d2d;
                color: #e0e0e0;
            }
            
            .greeting {
                color: #ffffff;
            }
            
            .intro-text, .info-text {
                color: #b0b0b0;
            }
            
            .url-container {
                background: #3d3d3d;
                border-color: #4d4d4d;
                color: #d0d0d0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="brand-logo">OUTFIT 818</div>
            <div style="color: rgba(255, 255, 255, 0.8); font-size: 14px; margin-top: 8px;">
                Your Style, Your Statement
            </div>
        </div>
        
        <!-- Content -->
        <div class="email-content">
            <!-- User Avatar -->
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            
            <!-- Greeting -->
            <h1 class="greeting">Hi {{ $user->name }},</h1>
            
            <!-- Introduction -->
            <p class="intro-text">
                We received a request to reset your password for your Outfit 818 account. 
                Click the button below to create a new, secure password:
            </p>
            
            <!-- Reset Button -->
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    Reset My Password
                </a>
            </div>
            
            
            <!-- Divider -->
            <div class="divider"></div>
            
            <!-- Expiry Notice -->
            <div class="expiry-notice">
                <div class="expiry-title">
                    <i>⏰</i>
                    <span>Important Notice</span>
                </div>
                <p class="expiry-text">
                    This password reset link will expire in <strong>60 minutes</strong> for security reasons. 
                    If you don't use it within this time, you'll need to request a new reset link.
                </p>
            </div>
            
            <!-- Security Notice -->
            <div class="info-box">
                <div class="info-title">
                    <i>🔒</i>
                    <span>Security Information</span>
                </div>
                <p class="info-text">
                    <strong>Didn't request this?</strong> If you didn't ask to reset your password, 
                    you can safely ignore this email. Your account remains secure.
                </p>
                <p class="info-text" style="margin-top: 10px;">
                    <strong>Need help?</strong> Contact our support team if you have any questions or concerns.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div style="margin-bottom: 20px;">
                <strong>Outfit 818</strong><br>
                Your fashion destination for premium styles
            </div>
            
            <div class="footer-links">
                <a href="{{ url('/') }}" class="footer-link">Visit Our Store</a>
                <a href="{{ url('/contact') }}" class="footer-link">Contact Support</a>
                <a href="{{ url('/privacy') }}" class="footer-link">Privacy Policy</a>
            </div>
            
            <div class="copyright">
                © {{ date('Y') }} Outfit 818. All rights reserved.<br>
                This email was sent to {{ $user->email }}
            </div>
        </div>
    </div>
</body>
</html>