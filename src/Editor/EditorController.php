<?php
declare(strict_types=1);

namespace FlocpressBuilder\Editor;

use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Core\ElementRegistry;
use FlocpressBuilder\Persistence\PageDataRepository;
use FlocpressBuilder\Sanitizer\PageDataSanitizer;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined('ABSPATH') || exit;

final class EditorController implements RegistrableInterface {

    private const NS = 'flocpress-builder/v1';

    public function __construct(
        private readonly PageDataRepository $repository,
        private readonly PageDataSanitizer $sanitizer,
        private readonly PostPermission $permission,
        private readonly ElementRegistry $registry
    ) {}

    public function register(): void {
        add_action('rest_api_init', [$this, 'routes']);
    }

    public function routes(): void {
        register_rest_route(self::NS, '/elements', [
            'methods'             => 'GET',
            'callback'            => [$this, 'elements'],
            'permission_callback' => static fn (): bool => current_user_can('edit_posts'),
        ]);

        register_rest_route(self::NS, '/load', [
            'methods'             => 'GET',
            'callback'            => [$this, 'load'],
            'permission_callback' => fn (WP_REST_Request $r): bool => $this->permission->can_read((int) $r->get_param('post_id')),
            'args'                => [
                'post_id' => ['required' => true, 'type' => 'integer', 'minimum' => 1],
            ],
        ]);

        register_rest_route(self::NS, '/save', [
            'methods'             => 'POST',
            'callback'            => [$this, 'save'],
            'permission_callback' => fn (WP_REST_Request $r): bool => $this->permission->can_edit((int) $r->get_param('post_id')),
            'args'                => [
                'post_id'   => ['required' => true, 'type' => 'integer', 'minimum' => 1],
                'page_data' => ['required' => true, 'type' => 'array'],
            ],
        ]);
    }

    public function elements(): WP_REST_Response {
        return new WP_REST_Response(['data' => $this->registry->editor_schemas()], 200);
    }

    public function load(WP_REST_Request $request): WP_REST_Response {
        return new WP_REST_Response([
            'data' => $this->repository->get((int) $request->get_param('post_id')),
        ], 200);
    }

    public function save(WP_REST_Request $request): WP_REST_Response|WP_Error {
        $post_id = (int) $request->get_param('post_id');

        if (!get_post($post_id)) {
            return new WP_Error('fpb_invalid_post', 'Post not found', ['status' => 404]);
        }

        $clean = $this->sanitizer->sanitize($request->get_param('page_data'));

        $this->repository->save($post_id, $clean);

        return new WP_REST_Response(['data' => $clean], 200);
    }
}