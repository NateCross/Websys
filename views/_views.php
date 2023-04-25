<?php

/**
 * Handles the views of the system.
 * Serves files alongside assigning variables to them
 */
class View
{
  private $data = [];

  private $template = null;

  public function __construct(String $template)
  {
    try {
      if (!str_ends_with($template, '.php'))
        $template = "$template.php";

      $file = "./" . strtolower($template);

      if (file_exists($file)) {
        $this->template = $file;
      } else {
        throw new Exception("View: $template not found");
      }
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }

  public function assign($variable, $value)
  {
    $this->data[$variable] = $value;
  }

  /**
   * Return: The templated file
   */
  public function render(): string | null
  {
    $content = null;

    try {
      extract($this->data);

      // Runs this in an output buffer
      // Improves performance
      ob_start();
      require($this->template);
      $content = ob_get_contents();
      ob_end_clean();
    } catch (Exception $e) {
      echo $e->getMessage();
    }

    return $content;
  }
}
