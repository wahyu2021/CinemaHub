<style>
    :root {
        --cursor-size: 20px;
        --cursor-outline-size: 40px;
    }

    body {
        background-color: #030303;
        color: #ffffff;
        overflow-x: hidden;
        cursor: none;
        /* Hide default cursor */
    }

    /* Futuristic Background Grid */
    .bg-grid {
        position: fixed;
        top: 0;
        left: 0;
        width: 200vw;
        height: 200vh;
        background-image:
            linear-gradient(rgba(229, 9, 20, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(229, 9, 20, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        transform: perspective(500px) rotateX(60deg) translateY(-100px) translateZ(-200px);
        animation: gridMove 20s linear infinite;
        z-index: -2;
        pointer-events: none;
    }

    @keyframes gridMove {
        0% {
            transform: perspective(500px) rotateX(60deg) translateY(0) translateZ(-200px);
        }

        100% {
            transform: perspective(500px) rotateX(60deg) translateY(50px) translateZ(-200px);
        }
    }

    /* Noise Texture */
    .noise-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9000;
        opacity: 0.03;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    }

    /* Custom Cursor */
    #cursor-dot,
    #cursor-outline {
        position: fixed;
        top: 0;
        left: 0;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        z-index: 9999;
        pointer-events: none;
    }

    #cursor-dot {
        width: var(--cursor-size);
        height: var(--cursor-size);
        background-color: #e50914;
        box-shadow: 0 0 10px #e50914;
    }

    #cursor-outline {
        width: var(--cursor-outline-size);
        height: var(--cursor-outline-size);
        border: 1px solid rgba(255, 255, 255, 0.5);
        transition: width 0.2s, height 0.2s, background-color 0.2s;
    }

    /* Glassmorphism */
    .glass {
        background: rgba(10, 10, 10, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .glass-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.03), rgba(255, 255, 255, 0.01));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #050505;
    }

    ::-webkit-scrollbar-thumb {
        background: #e50914;
        border-radius: 10px;
    }

    /* Utilities */
    .text-glow {
        text-shadow: 0 0 20px rgba(229, 9, 20, 0.6);
    }

    /* 3D Tilt Wrapper */
    .tilt-card {
        transform-style: preserve-3d;
        transform: perspective(1000px);
    }

    .tilt-content {
        transform: translateZ(20px);
    }
</style>
