<?php
register_shutdown_function('my_shutdown');
 
function my_shutdown()
{
    echo AmuLog::fetch_output();
}
 
class AmuLog {
    private static $output = '';
    static function log($data)
    {
        if (is_array($data) || is_object($data))
        {
            $data = json_encode($data);
        }
        ob_start();
        ?>
        <?php if (self::$output === ''):?>
        <script>
        <?php endif;?>
        console.log('<?=$data;?>');
        <?php
        self::$output .= ob_get_contents();
        ob_end_clean();
    }
    static function fetch_output()
    {
        if (self::$output !== '')
        {
            self::$output .= '</script>';
        }
        return self::$output;
    }
}