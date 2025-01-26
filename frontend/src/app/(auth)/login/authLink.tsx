import Link from "next/link";

type Props = {
    href: "/signup" | "/login";
}

const AuthLink = ({href}: Props) => {
    const pText = href === "/signup" ? "Don't have an account?" : "Already have an account?";
    return (
        <p className="mt-4 text-sm text-center text-gray-600">
            {pText}{' '}
            <Link href={href} className="text-blue-600 hover:underline">
                {href === "/signup" ? "Sign up" : "Log in"}
            </Link>
        </p>
    );
}

export default AuthLink;