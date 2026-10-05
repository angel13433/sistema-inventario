// Utilidades de formato para la interfaz (locale venezolano).

const numberFormatter = new Intl.NumberFormat('es-VE');

const decimalFormatter = new Intl.NumberFormat('es-VE', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

export const formatNumber = (value) => numberFormatter.format(Number(value) || 0);

export const formatUsd = (value) => `$ ${decimalFormatter.format(Number(value) || 0)}`;

export const formatVes = (value) => `Bs. ${decimalFormatter.format(Number(value) || 0)}`;
