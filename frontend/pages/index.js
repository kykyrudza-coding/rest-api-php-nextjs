import { motion } from 'framer-motion';

export default function Home({ message }) {
    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.3 }}
        >
            <h1>{message || 'Loading...'}</h1>
        </motion.div>
    );
}

export async function getServerSideProps() {
    let message = '';

    try {
        const res = await fetch('http://localhost:8000/123');
        const data = await res.json();
        if (data.error) {
            message = data.error;
        } else {
            message = data.message;
        }
    } catch (err) {
        console.error(err);
    }

    return {
        props: {
            message,
        },
    };
}