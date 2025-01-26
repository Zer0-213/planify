type Props = {
    text: string;
}

const Header = ({text}: Props) => {
    return (
        <h1 className="text-2xl font-semibold text-center text-gray-800">{text}</h1>
    );
}

export default Header;