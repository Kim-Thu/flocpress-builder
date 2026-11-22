<?php
declare(strict_types=1);

namespace FlocpressBuilder\Admin;

use FlocpressBuilder\Contracts\RegistrableInterface;
use FlocpressBuilder\Persistence\PageDataRepository;
use WP_Post;

defined('ABSPATH') || exit;

final class EditorMetaBox implements RegistrableInterface {

    private const NONCE_ACTION = 'fpb_metabox';
    private const NONCE_FIELD  = 'fpb_metabox_nonce';

    public function __construct(private readonly PageDataRepository $repository) {}

    public function register(): void {
        add_action('add_meta_boxes', [$this, 'add']);
        add_action('save_post', [$this, 'save'], 10, 2);
    }

    public function add(): void {
        add_meta_box(
            'fpb-editor-metabox',
            __('Flocpress Builder', 'flocpress-builder'),
            [$this, 'render'],
            ['post', 'page'],
            'normal',
            'high'
        );
    }

    public function render(WP_Post $post): void {
        wp_nonce_field(self::NONCE_ACTION, self::NONCE_FIELD);
        ?>
        <p>
            <label>
                <input type="checkbox" name="fpb_is_active" value="1"
                    <?php checked($this->repository->is_active($post->ID)); ?>>
                <?php esc_html_e('Dung Flocpress Builder cho trang nay', 'flocpress-builder'); ?>
            </label>
        </p>
        <div id="fpb-editor-root" class="p-editor js-mount"
             data-post-id="<?php echo esc_attr((string) $post->ID); ?>"></div>
        <?php
    }

    public function save(int $post_id, WP_Post $post): void {
        $nonce = $_POST[self::NONCE_FIELD] ?? '';

        if (!is_string($nonce) || !wp_verify_nonce($nonce, self::NONCE_ACTION)) {
            return;
        }

        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $this->repository->set_active($post_id, isset($_POST['fpb_is_active']));
    }
}