import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useLocalizationFrontend(entity, lang) {
    const page = usePage();

    const localization = computed(() => {
        if (!entity.value?.localizations) return null;
        const languages = page.props.languages ?? [];

        if (!languages.length) return entity.value.localizations[0] || null;
        const langObj = languages.find(l => l.code === lang.value);

        if (!langObj) return entity.value.localizations[0] || null;
        return entity.value.localizations.find(
            loc => String(loc.language_id) === String(langObj.id)
        ) || entity.value.localizations[0] || null;
    });

    const parsedContent = computed(() => {
        const content = localization.value?.content;
        if (!content) return [];
        
        if (Array.isArray(content)) return content;

        if (typeof content === 'string') {
            try { return JSON.parse(content); } catch { return []; }
        }
        return [];
    });

    return { localization, parsedContent };
}
