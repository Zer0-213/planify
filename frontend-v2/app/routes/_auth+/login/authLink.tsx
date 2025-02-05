import {Link} from "react-router";

type Props = {
    href: "/signup" | "/login";
}

const authLink = ({href}: Props) => {
    const pText = href === "/signup" ? "Don't have an account?" : "Already have an account?";
    return (
        <p className="mt-4 text-sm text-center text-gray-600">
            {pText}{' '}
            <Link to={href} className="text-blue-600 hover:underline">
                {href === "/signup" ? "Sign up" : "Log in"}
            </Link>
        </p>
    );
}

export default authLink;