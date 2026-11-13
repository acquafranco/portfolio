<?php

namespace App\Portfolio;

/**
 * Everything the public site reports to the first-party analytics. The value
 * is what the browser sends (data-track="cv_download").
 */
enum TrackedEvent: string
{
    case PageView = 'pageview';
    case CvDownload = 'cv_download';
    case WhatsappClick = 'whatsapp_click';
    case EmailClick = 'email_click';
    case EmailCopy = 'email_copy';
    case ProjectLink = 'project_link';
    case GithubClick = 'github_click';

    /**
     * Admin panel label (the panel is in Spanish).
     */
    public function label(): string
    {
        return match ($this) {
            self::PageView => 'Páginas vistas',
            self::CvDownload => 'Descargas del CV',
            self::WhatsappClick => 'Clics en WhatsApp',
            self::EmailClick => 'Clics en el email',
            self::EmailCopy => 'Email copiado',
            self::ProjectLink => 'Visitas a un producto en vivo',
            self::GithubClick => 'Clics en GitHub',
        };
    }

    /**
     * Events that mean someone tried to get in touch.
     *
     * @return list<self>
     */
    public static function contactIntents(): array
    {
        return [self::WhatsappClick, self::EmailClick, self::EmailCopy];
    }

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
