<?php

if (!class_exists('MV_Slider_Settings')) {
    class MV_Slider_Settings
    {
        public static $options;

        public function __construct()
        {
            self::$options = get_option('mv_slider_options');
            add_action('admin_init', [$this, 'admin_init']);
        }

        public function admin_init()
        {
            register_setting(
                'mv_slider_group',
                'mv_slider_options',
                [$this, 'mv_slider_validate']
            );

            add_settings_section(
                'mv_slider_main_section',
                __('How does it work?', 'mv-slider'),
                null,
                'mv_slider_page1'
            );

            add_settings_section(
                'mv_slider_second_section',
                __('Other Plugin Options', 'mv-slider'),
                null,
                'mv_slider_page2'
            );

            add_settings_field(
                'mv_slider_shortcode',
                __('Shortcode', 'mv-slider'),
                [$this, 'mv_slider_shortcode_callback'],
                'mv_slider_page1',
                'mv_slider_main_section'
            );

            add_settings_field(
                'mv_slider_title',
                __('Slider Title', 'mv-slider'),
                [$this, 'mv_slider_title_callback'],
                'mv_slider_page2',
                'mv_slider_second_section',
                [
                    'label_for' => 'mv_slider_title',
                ]
            );

            add_settings_field(
                'mv_slider_bullets',
                __('Display Bullets', 'mv-slider'),
                [$this, 'mv_slider_bullets_callback'],
                'mv_slider_page2',
                'mv_slider_second_section',
                [
                    'label_for' => 'mv_slider_bullets',
                ]
            );

            add_settings_field(
                'mv_slider_style',
                __('Slider Style', 'mv-slider'),
                [$this, 'mv_slider_style_callback'],
                'mv_slider_page2',
                'mv_slider_second_section',
                [
                    'items' => [
                        'style-1',
                        'style-2',
                    ],
                    'label_for' => 'mv_slider_style',
                ]
            );
        }

        public function mv_slider_shortcode_callback()
        {
            ?>
            <span>
                <?php _e('Use the shortcode [mv_slider] to display the slider in any page/post/widget', 'mv-slider'); ?>
            </span>
            <?php
        }

        public function mv_slider_title_callback($args)
        {
            ?>
                <input 
                    type="text" 
                    name="mv_slider_options[mv_slider_title]" 
                    id="mv_slider_title"
                    value="<?php echo isset(self::$options['mv_slider_title']) ? esc_attr(self::$options['mv_slider_title']) : ''; ?>"
                >
            <?php
        }

        public function mv_slider_bullets_callback($args)
        {
            ?>
                <input 
                    type="checkbox"
                    name="mv_slider_options[mv_slider_bullets]"
                    id="mv_slider_bullets"
                    value="1"
                    <?php
                        if (isset(self::$options['mv_slider_bullets'])) {
                            checked('1', self::$options['mv_slider_bullets'], true);
                        }
            ?>
                />
                <label for="mv_slider_bullets">
                    <?php _e('Whether to display bullets or not', 'mv-slider'); ?>
                </label>
                
            <?php
        }

        public function mv_slider_style_callback($args)
        {
            ?>
            <select 
                id="mv_slider_style" 
                name="mv_slider_options[mv_slider_style]">
                <?php
                foreach ($args['items'] as $item) {
                    ?>
                    <option value="<?php echo esc_attr($item); ?>" 
                        <?php
                            isset(self::$options['mv_slider_style'])
                            ? selected($item, self::$options['mv_slider_style'], true)
                            : '';
                    ?>
                    >
                        <?php echo esc_html(ucfirst($item)); ?>
                    </option>                
                <?php } ?>
            </select>
            <?php
        }

        public function mv_slider_validate($input)
        {
            $new_input = [];
            foreach ($input as $key => $value) {
                switch ($key) {
                    case 'mv_slider_title':
                        if (empty($value)) {
                            add_settings_error(
                                'mv_slider_options',
                                'mv_slider_message',
                                __('The title field can not be left empty', 'mv-slider'),
                                'error'
                            );
                            $value = __('Please, type some text', 'mv-slider');
                        }
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                    default:
                        $new_input[$key] = sanitize_text_field($value);
                        break;
                }
            }

            return $new_input;
        }
    }
}
