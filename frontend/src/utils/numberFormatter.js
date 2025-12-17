export const safeNumber = (value, fallback = 0) => {
  if (value === null || value === undefined || value === '') {
    return fallback;
  }
  
  const num = Number(value);

  if (isNaN(num)) {
    return fallback;
  }
  
  return num;
};

export const formatDecimal = (value, decimals = 8) => {
  const num = safeNumber(value);
  return num.toFixed(decimals);
};

export const parseDecimal = (value) => {
  if (typeof value === 'number') {
    return value;
  }
  
  if (typeof value === 'string') {
    const cleanValue = value.replace(/,/g, '');
    return Number(cleanValue);
  }
  
  return 0;
};