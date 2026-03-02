<?php

namespace App\Controller;

use App\Dto\Response\ErrorResponse;
use App\Dto\Response\LocalesResponse;
use App\Dto\Response\TranslationsResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dictionaries')]
class DictionaryController extends DefaultController
{
    private const AVAILABLE_LOCALES = ['en', 'pl'];
    private const DOMAINS = ['messages', 'validators', 'security'];

    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    #[Route('/locales', name: 'dictionaries_locales', methods: ['GET'])]
    #[OA\Get(
        path: '/api/dictionaries/locales',
        summary: 'Get available locales',
        description: 'Returns list of available language codes',
        security: []
    )]
    #[OA\Response(
        response: 200,
        description: 'List of available locales',
        content: new Model(type: LocalesResponse::class)
    )]
    #[OA\Tag(name: 'Dictionary')]
    public function locales(): JsonResponse
    {
        return $this->response(new LocalesResponse(self::AVAILABLE_LOCALES));
    }

    #[Route('/translations/{locale}', name: 'dictionaries_translations', methods: ['GET'])]
    #[OA\Get(
        path: '/api/dictionaries/translations/{locale}',
        summary: 'Get all translations for locale',
        description: 'Returns all translations grouped by domain (messages, validators, security)',
        security: []
    )]
    #[OA\Parameter(
        name: 'locale',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', enum: ['en', 'pl'])
    )]
    #[OA\Response(
        response: 200,
        description: 'Translations grouped by domain',
        content: new Model(type: TranslationsResponse::class)
    )]
    #[OA\Response(response: 400, description: 'Invalid locale', content: new Model(type: ErrorResponse::class))]
    #[OA\Tag(name: 'Dictionary')]
    public function translations(string $locale): JsonResponse
    {
        if (!in_array($locale, self::AVAILABLE_LOCALES)) {
            throw new BadRequestHttpException($this->translator->trans('error.invalid_locale', [], 'messages'));
        }

        $response = new TranslationsResponse(
            $this->loadDomainTranslations('messages', $locale),
            $this->loadDomainTranslations('validators', $locale),
            $this->loadDomainTranslations('security', $locale)
        );

        return $this->response($response);
    }

    private function loadDomainTranslations(string $domain, string $locale): array
    {
        $catalogue = $this->translator->getCatalogue($locale);
        return $catalogue->all($domain);
    }
}
