export function formatUSD(amount) {
    if (amount === null || amount === undefined || amount === "") {
        return "";
    }

    const number = Number(amount);

    if (isNaN(number)) {
        return "";
    }

    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(number);
}
