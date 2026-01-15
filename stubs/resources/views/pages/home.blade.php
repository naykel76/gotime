<x-layouts.base :title="$title ?? null">
    <style>
        h1 {
            font-size: 5rem;
            font-weight: 900;
            background: linear-gradient(45deg, #fff, #154901, #ffd700, #fff);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.1em;
            animation: shimmer 10s ease-in-out infinite, bounce 1s ease-in-out 0.8s;
            text-shadow: 0 0 60px rgba(255, 255, 255, 0.3);
        }

        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
        }

        /* Subtle grid overlay */
        .bg-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 20s linear infinite;
        }



        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .content {
            text-align: center;
            z-index: 10;
            animation: floatIn 0.8s ease-out;
        }

        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @keyframes shimmer {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .particle:nth-child(1) {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            top: 20%;
            left: 10%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(2) {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.08);
            top: 60%;
            left: 80%;
            animation: float 8s ease-in-out infinite 1s;
        }

        .particle:nth-child(3) {
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.05);
            top: 70%;
            left: 15%;
            animation: float 10s ease-in-out infinite 2s;
        }

        .particle:nth-child(4) {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.12);
            top: 15%;
            left: 75%;
            animation: float 7s ease-in-out infinite 1.5s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }

            50% {
                transform: translateY(-30px) rotate(180deg);
                opacity: 0.8;
            }
        }


        @keyframes gridMove {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }  
    </style>
    <div class="bg-gradient overflow-hidden">
        <div class="flex-centered h-screen">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>

            <div class="content">
                <img src="{{ asset('logo.svg') }}" class="h-5" alt="Naykel logo" />
                <h1 class="mt-2">GOTIME</h1>
            </div>
        </div>
    </div>
</x-layouts.base>