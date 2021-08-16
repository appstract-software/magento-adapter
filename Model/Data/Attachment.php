<?php

namespace Appstractsoftware\MagentoAdapter\Model\Data;

use Appstractsoftware\MagentoAdapter\Api\Data\AttachmentInterface;

class Attachment implements AttachmentInterface
{

  private $fileType;
  private $fileName;
  private $content;
  private $disposition;
  private $encoding;

  public function setFileType($fileType)
  {
    $this->fileType = $fileType;
  }

  /**
   * @return string
   */
  public function setFileName($fileName)
  {
    $this->fileName = $fileName;
  }

  /**
   * @return string
   */
  public function setContent($content)
  {
    $this->content = $content;
  }

  /**
   * @return string
   */
  public function setDisposition($disposition)
  {
    $this->disposition = $disposition;
  }

  /**
   * @return string
   */
  public function setEncoding($encoding)
  {
    $this->encoding = $encoding;
  }

  public function getFileType()
  {
    return $this->fileType;
  }

  /**
   * @return string
   */
  public function getFileName()
  {
    return $this->fileName;
  }

  /**
   * @return string
   */
  public function getContent()
  {
    return $this->content;
  }

  /**
   * @return string
   */
  public function getDisposition()
  {
    return $this->disposition;
  }

  /**
   * @return string
   */
  public function getEncoding()
  {
    return $this->encoding;
  }
}
