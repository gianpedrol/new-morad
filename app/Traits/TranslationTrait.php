<?php
namespace App\Traits;
use Stichoza\GoogleTranslate\GoogleTranslate;
trait TranslationTrait
{
    /**
     * Updates the default translation for a given reference class.
     *
     * @param int $id The ID of the reference.
     * @param string $code The language code.
     * @param string $translationClass The translation class.
     * @param string $referenceClass The reference class.
     * @param string $forignId The foreign ID.
     * @param mixed ...$updateFields The fields to update.
     * @return bool Returns true if the translation is successfully updated, false otherwise.
     */
    protected function updateDefaultTranslation(int $id, string $code, string $translationClass, string $referenceClass, string $forignId, ...$updateFields)
    {
        $translationClass = '\\App\\Models\\' . $translationClass;
        $referenceClass = '\\App\\Models\\' . $referenceClass;
        $translation = $translationClass::firstOrCreate([
            'language_code' => $code,
            $forignId => $id
        ]);
        $section = $referenceClass::find($id);
        if ($section) {
            foreach ($updateFields as $field) {
                $translation->$field = $section->$field;
            }
            return $translation->save();
        }
        return false;
    }
    /**
     * Creates or updates a translation from Google Translate.
     *
     * @param int $id The ID of the translation.
     * @param string $code The language code of the translation.
     * @param string $translationClass The translation class name.
     * @param string $referenceClass The reference class name.
     * @param string $forignId The foreign ID.
     * @param mixed ...$updateFields The fields to update.
     * @return mixed The updated translation or null if not found.
     */
    protected function createAndUpdateFromGoogleTranslation(int $id, string $code, string $translationClass, string $referenceClass, string $forignId, ...$updateFields)
    {
        $translationClass = '\\App\\Models\\' . $translationClass;
        $referenceClass = '\\App\\Models\\' . $referenceClass;
        $section = $referenceClass::find($id);
        if($section){
            $translation = $translationClass::firstOrCreate([
                $forignId => $id,
                'language_code' => $code
            ]);
            if ($translation) {
                $tr = new GoogleTranslate($code);
                foreach ($updateFields as $field) {
                    if(!$translation->$field){
                        $translation->$field = $tr->translate($section->$field) ?? $section->$field;
                    }
                }
                $translation->save();
            }
            return $translation ?? null;
        }
        return null;
    }
}
