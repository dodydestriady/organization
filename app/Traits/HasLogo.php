<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasLogo
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateLogo(UploadedFile $photo)
    {
        tap($this->logo, function ($previous) use ($photo) {
            $this->forceFill([
                'logo' => $photo->storePublicly(
                    'organization-logos', ['disk' => $this->logoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->logoDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo
                    ? Storage::disk($this->logoDisk())->url($this->logo)
                    : $this->defaultLogoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultLogoUrl()
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function logoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('organization.logo_disk', 'public');
    }
}
