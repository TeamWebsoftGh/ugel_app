<?php

namespace App\Helpers;

class DataTableActionHelper
{
    public static function generate($id, array $options = [], array $additionalLinks = []): string
    {
        $html = '<div class="dropdown">
                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">';

        $builtIn = [
            'view' => ['label' => 'View', 'icon' => 'ri-eye-fill', 'defaultClass' => 'dt-show'],
            'edit' => ['label' => 'Edit', 'icon' => 'ri-pencil-fill', 'defaultClass' => 'dt-edit'],
            'delete' => ['label' => 'Delete', 'icon' => 'ri-delete-bin-fill', 'defaultClass' => 'dt-delete', 'divider' => true],
        ];

        foreach ($builtIn as $key => $meta) {
            $config = $options[$key] ?? null;

            if ($config === false) {
                continue;
            }

            if (!empty($meta['divider'])) {
                $html .= '<li class="dropdown-divider"></li>';
            }

            $link = is_array($config)
                ? array_merge(['url' => 'javascript:void(0);', 'class' => $meta['defaultClass'], 'attributes' => []], $config)
                : ['url' => 'javascript:void(0);', 'class' => $meta['defaultClass'], 'attributes' => []];

            $html .= self::renderItem([
                'label' => $meta['label'],
                'icon' => $meta['icon'],
                'url' => $link['url'],
                'class' => $link['class'],
                'data-id' => $id,
                'attributes' => $link['attributes'] ?? [],
            ]);
        }

        // Custom links
        foreach ($additionalLinks as $link) {
            if (isset($link['condition']) && !$link['condition']) continue;
            if (!empty($link['divider'])) $html .= '<li class="dropdown-divider"></li>';

            $html .= self::renderItem(array_merge([
                'label' => 'Action',
                'icon' => 'ri-more-fill',
                'class' => '',
                'url' => 'javascript:void(0);',
                'data-id' => $id,
                'attributes' => [],
            ], $link));
        }

        $html .= '</ul></div>';
        return $html;
    }

    protected static function renderItem(array $item): string
    {
        $attrs = [
            'href="' . $item['url'] . '"',
            'class="dropdown-item ' . $item['class'] . '"',
            'data-id="' . $item['data-id'] . '"'
        ];

        foreach ($item['attributes'] ?? [] as $key => $val) {
            $attrs[] = $key . '="' . htmlspecialchars($val) . '"';
        }

        return '<li>
            <a ' . implode(' ', $attrs) . '>
                <i class="' . $item['icon'] . ' align-bottom me-2 text-muted"></i> ' . e($item['label']) . '
            </a>
        </li>';
    }
}
