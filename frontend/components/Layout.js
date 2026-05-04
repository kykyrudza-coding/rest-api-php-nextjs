import React from 'react';
import Head from 'next/head';

const Layout = ({ children }) => {
    const currentYear = new Date().getFullYear();

    return (
        <>
            <Head>
                <title>PHP Next API</title>
                <meta name="description" content="Simple PHP API with a Next.js frontend" />
            </Head>
            <div className="flex min-h-screen flex-col bg-slate-50 text-slate-950">
                <header className="border-b border-slate-200 bg-white">
                    <div className="mx-auto flex w-full max-w-5xl items-center justify-between px-6 py-4">
                        <span className="text-sm font-semibold uppercase tracking-wide text-slate-700">
                            PHP Next API
                        </span>
                    </div>
                </header>
                <main className="flex flex-1 items-center px-6 py-12">
                    {children}
                </main>
                <footer className="border-t border-slate-200 bg-white px-6 py-4 text-center text-sm text-slate-500">
                    &copy; {currentYear} Example Next - PHP App
                </footer>
            </div>
        </>
    );
};

export default Layout;
