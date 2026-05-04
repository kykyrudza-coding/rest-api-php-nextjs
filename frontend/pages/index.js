import { useEffect, useState } from 'react';
import { motion } from 'framer-motion';

const initialApiState = {
    error: '',
    isLoading: true,
    message: '',
};

export default function Home() {
    const [{ error, isLoading, message }, setApiState] = useState(initialApiState);
    const hasError = Boolean(error);

    useEffect(() => {
        let isMounted = true;

        async function loadApiStatus() {
            try {
                const res = await fetch('/api/health', {
                    headers: {
                        Accept: 'application/json',
                    },
                });
                const contentType = res.headers.get('content-type') || '';

                if (! contentType.includes('application/json')) {
                    throw new Error('Backend returned a non-JSON response.');
                }

                const data = await res.json();

                if (! res.ok || data.error) {
                    throw new Error(data.error || `Backend responded with HTTP ${res.status}.`);
                }

                if (isMounted) {
                    setApiState({
                        error: '',
                        isLoading: false,
                        message: data.message || 'API responded successfully',
                    });
                }
            } catch (err) {
                console.error(err);

                if (isMounted) {
                    setApiState({
                        error: 'Could not connect to the backend API.',
                        isLoading: false,
                        message: '',
                    });
                }
            }
        }

        loadApiStatus();

        return () => {
            isMounted = false;
        };
    }, []);

    return (
        <motion.div
            className="mx-auto flex w-full max-w-3xl flex-col gap-6"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.3 }}
        >
            <section className="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <p className="text-sm font-medium uppercase tracking-wide text-slate-500">
                    API status
                </p>
                <h1 className="mt-3 text-3xl font-semibold text-slate-950">
                    {isLoading && 'Checking backend...'}
                    {! isLoading && hasError && 'Backend unavailable'}
                    {! isLoading && ! hasError && message}
                </h1>
                {hasError && (
                    <p className="mt-3 text-sm text-red-700">
                        {error}
                    </p>
                )}
            </section>
        </motion.div>
    );
}
