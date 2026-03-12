/**
 * Utilidades para íconos y banderas.
 */

/**
 * Convierte un código ISO 3166-1 alpha-2 a emoji de bandera.
 * 
 * Cómo funciona:
 * - Los emojis de bandera se codifican como dos caracteres especiales Unicode
 * - Cada carácter uppercase ASCII se convierte a "Regional Indicator"
 * - "es" (España) → 🇪🇸, "us" (Estados Unidos) → 🇺🇸, "mx" (México) → 🇲🇽
 * 
 * @param {string} countryCode - Código ISO de dos letras (ej: "es", "us", "mx")
 * @returns {string} Emoji de bandera o globo si el código es inválido
 */
export function getFlagEmoji(countryCode) {
    if (!countryCode) {
        return "🌐"; // Globo por defecto
    }
    
    return countryCode
        .toUpperCase()
        .split("")
        .map((c) => String.fromCodePoint(127397 + c.charCodeAt(0)))
        .join("");
}
