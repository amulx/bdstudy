<?php
class Tool {
  public static function log($info) {
    $time = date('m-d H:i:s');
    $backtrace = debug_backtrace();
    $backtrace_line = array_shift($backtrace); // 哪一行调用的log方法
    $backtrace_call = array_shift($backtrace); // 谁调用的log方法
    $file = substr($backtrace_line['file'], strlen($_SERVER['DOCUMENT_ROOT']));
    $line = $backtrace_line['line'];
    $class = isset($backtrace_call['class']) ? $backtrace_call['class'] : '';
    $type = isset($backtrace_call['type']) ? $backtrace_call['type'] : '';
    $func = $backtrace_call['function'];
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/debug.log', "$time $file:$line $class$type$func: $info\n", FILE_APPEND);
  }
}
class Action {
  public function a() {
    $this->b();
  }
  public function b() {
    $this->c();
  }
  public function c() {
    Tool::log('sdfsdf');
  }
}
$action = new Action();
$action->a();



function loginfo($format) {
  $args = func_get_args();
  array_shift($args);
  $d = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];
  $info = vsprintf($format, $args);
  $data = sprintf("%s %s,%d: %s\n", date("Ymd His"), $d["file"], $d["line"], $info);
  file_put_contents(__DIR__."/log.txt", $data, FILE_APPEND);
}