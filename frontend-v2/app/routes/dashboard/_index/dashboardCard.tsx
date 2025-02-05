type Props = {
    header: string;
    text: string
    textColor: "red" | "green" | "blue" | "yellow";
}

const DashboardCard = ({header, text, textColor}: Props) => {
    let textColorClass = "";
    switch (textColor) {
        case "red":
            textColorClass = "text-red-500";
            break;
        case "green":
            textColorClass = "text-green-500";
            break;
        case "blue":
            textColorClass = "text-blue-500";
            break;
        case "yellow":
            textColorClass = "text-yellow-500";
            break;
    }
    return (
        <div className="bg-white shadow-md rounded-lg p-4 flex-1 text-center">
            <h2 className="text-lg font-semibold">{header}</h2>
            <p className={`text-3xl font-bold ${textColorClass}`}>{text}</p>
        </div>
    )
}

export default DashboardCard;