/**
 * Utilidades para trabajo con datos de localización.
 * 
 * Una "localización" es la combinación de:
 * - Mercado (ej: "es" para España)
 * - Idioma (ej: "es" para español)
 * - Contenido (título, slug, extracto, etc.)
 */

/**
 * Construye la clave que identifica una localización específica.
 * 
 * Clave: "{marketCode}_{languageCode}"
 * Ejemplos: "es_es", "mx_es", "us_en"
 * 
 * @param {string} marketCode - Código de mercado (ej: "es", "mx")
 * @param {string} languageCode - Código de idioma (ej: "es", "en")
 * @returns {string} Clave única (ej: "es_es")
 */
export function buildLocalizationKey(marketCode, languageCode) {
    if (!marketCode || !languageCode) {
        return null;
    }
    return `${marketCode}_${languageCode}`;
}

/**
 * Crea el objeto de localización vacío con la estructura inicial.
 * 
 * Estructura base que debe tener cada localización, sin importar
 * qué módulo la use (artículos, productos, tratamientos, etc.).
 * 
 * Los módulos pueden pasar `extraFields` para añadir campos propios:
 * - Tratamientos: indications, contraindications
 * - Productos: sku, precio
 * 
 * @param {number} marketId - ID del mercado
 * @param {number} languageId - ID del idioma
 * @param {Object} extraFields - Campos adicionales del módulo
 * @returns {Object} Objeto de localización
 */
export function createEmptyLocalization(marketId, languageId, extraFields = {}) {
    return {
        market_id: marketId,
        language_id: languageId,
        title: "",
        slug: "",
        excerpt: "",
        content: [],
        seo_metadata: { 
            title: "", 
            description: "" 
        },
        ...extraFields,
    };
}

/**
 * Construye la estructura completa de localizaciones inicial.
 * 
 * Recorre todos los mercados y sus idiomas para crear un objeto vacío
 * por cada combinación. Esto garantiza que Vue pueda ser reactivo desde
 * el inicio: los campos ya existen aunque estén vacíos.
 * 
 * @param {Array} markets - Array de mercados con idiomas
 * @param {Object} extraFields - Campos adicionales para el módulo
 * @returns {Object} Objeto con claves como "es_es", "mx_es", etc.
 */
export function buildAllLocalizations(markets = [], extraFields = {}) {
    const result = {};
    
    for (const market of markets) {
        for (const lang of market.languages ?? []) {
            const key = buildLocalizationKey(market.code, lang.code);
            if (key) {
                result[key] = createEmptyLocalization(
                    market.id,
                    lang.id,
                    extraFields
                );
            }
        }
    }
    
    return result;
}

/**
 * Carga localizaciones existentes (para formularios de EDICIÓN).
 * 
 * Cuando el backend envía datos existentes, esta función los carga
 * en la estructura esperada. Busca el código de mercado/idioma a partir
 * de los IDs.
 * 
 * @param {Object} existingData - Las localizaciones guardadas
 * @param {Array} markets - Array de mercados con idiomas
 * @param {Object} targetObject - Dónde guardar (el ref.value)
 * @returns {void} Modifica targetObject directamente
 */
export function loadExistingLocalizations(existingData, markets, targetObject) {
    if (!Array.isArray(existingData)) {
        return;
    }
    
    for (const loc of existingData) {
        // Busca qué mercado e idioma corresponden a estos IDs
        const market = markets.find((m) => m.id === loc.market_id);
        const lang = market?.languages.find((l) => l.id === loc.language_id);
        
        if (!market || !lang) {
            console.warn(`No se encontró mercado/idioma para localización`, loc);
            continue;
        }
        
        const key = buildLocalizationKey(market.code, lang.code);
        if (key && targetObject[key]) {
            // Carga los datos existentes
            Object.assign(targetObject[key], loc);
        }
    }
}

/**
 * Filtra localizaciones para enviar solo las que tienen contenido.
 * 
 * Las localizaciones vacías (sin título) no se envían al backend.
 * No tiene sentido crear filas en la tabla con todo en blanco.
 * 
 * @param {Object} localizations - Objeto de localizaciones
 * @returns {Array} Array de localizaciones con contenido
 */
export function getFilledLocalizations(localizations) {
    return Object.values(localizations).filter((loc) =>
        loc.title?.trim()
    );
}

/**
 * Comprueba si una localización específica tiene contenido.
 * 
 * @param {Object} localizations - Objeto de localizaciones
 * @param {string} marketCode - Código de mercado
 * @param {string} languageCode - Código de idioma
 * @returns {boolean} true si tiene título
 */
export function hasLocalizationContent(localizations, marketCode, languageCode) {
    const key = buildLocalizationKey(marketCode, languageCode);
    return !!localizations[key]?.title?.trim();
}

/**
 * Comprueba si un mercado tiene contenido en ALGÚN idioma.
 * 
 * Usado para mostrar indicador visual de si un mercado está completo.
 * 
 * @param {Object} localizations - Objeto de localizaciones
 * @param {Array} markets - Array de mercados
 * @param {string} marketCode - Código del mercado a verificar
 * @returns {boolean} true si algún idioma tiene contenido
 */
export function hasMarketContent(localizations, markets, marketCode) {
    const market = markets.find((m) => m.code === marketCode);
    if (!market) return false;
    
    return market.languages.some((lang) =>
        hasLocalizationContent(localizations, marketCode, lang.code)
    );
}

/**
 * Cuenta cuántas localizaciones tienen contenido.
 * 
 * Usado para mostrar en el sidebar: "2/8 localizaciones completadas"
 * 
 * @param {Object} localizations - Objeto de localizaciones
 * @returns {number} Cantidad de localizaciones con título
 */
export function countFilledLocalizations(localizations) {
    return Object.values(localizations).filter((loc) =>
        loc.title?.trim()
    ).length;
}
