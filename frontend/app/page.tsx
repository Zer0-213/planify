import Link from "next/link";

export default function Home() {
    return (
        <div
            className="grid grid-rows-[20px_1fr_20px] items-center justify-items-center min-h-screen p-8 pb-20 gap-16 sm:p-20 font-[family-name:var(--font-geist-sans)]">
            <main className="flex flex-col gap-8 row-start-2 items-center sm:items-start w-full">
                <div className="flex flex-col justify-between min-h-screen bg-gray-50 w-full">
                    <div>
                        <header className="bg-white shadow">
                            <div className="container mx-auto px-6 py-4 flex justify-between items-center">
                                <h1 className="text-xl font-bold text-gray-800">Planify</h1>
                                <nav>
                                    <Link href="/login" className="text-gray-600 hover:text-gray-900 mr-4">Login</Link>
                                    <Link href="/signup" className="btn btn-primary">Sign Up</Link>
                                </nav>
                            </div>
                        </header>

                        <section
                            className="flex flex-col items-center justify-center text-center py-20 px-6 bg-indigo-600 text-white">
                            <h1 className="text-4xl font-bold mb-4">Welcome to Planify</h1>
                            <p className="text-lg mb-8">Staff scheduling made easier.</p>
                            <div className="space-x-4">
                                <Link href="/signup" className="btn btn-lg btn-white">Get Started</Link>
                                <Link href="/features" className="btn btn-lg btn-outline">Learn More</Link>
                            </div>
                        </section>
                    </div>

                    <section className="py-12 bg-gray-100">
                        <div className="container mx-auto px-6">
                            <h2 className="text-2xl font-bold text-gray-800 mb-6">Features</h2>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="p-4 bg-white shadow rounded">
                                    <h3 className="text-lg font-semibold text-gray-800 mb-2">Feature 1</h3>
                                    <p className="text-gray-600">Brief description of this feature.</p>
                                </div>
                                <div className="p-4 bg-white shadow rounded">
                                    <h3 className="text-lg font-semibold text-gray-800 mb-2">Feature 2</h3>
                                    <p className="text-gray-600">Brief description of this feature.</p>
                                </div>
                                <div className="p-4 bg-white shadow rounded">
                                    <h3 className="text-lg font-semibold text-gray-800 mb-2">Feature 3</h3>
                                    <p className="text-gray-600">Brief description of this feature.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <footer className="bg-gray-800 text-white py-6">
                        <div className="container mx-auto px-6 text-center">
                            <p>&copy; {new Date().getFullYear()} Planify. All rights reserved.</p>
                        </div>
                    </footer>
                </div>
            </main>
        </div>
    );
}
