/** @type {import('next').NextConfig} */
const backendUrl = process.env.BACKEND_URL || process.env.PHP_API_URL || 'http://localhost:8000';

const nextConfig = {
    reactStrictMode: true,

    async rewrites() {
        return [
            {
                source: '/api/:path*',
                destination: `${backendUrl}/:path*`,
            },
        ];
    },
};

module.exports = nextConfig;
