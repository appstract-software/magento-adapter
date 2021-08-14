<?php

namespace Appstractsoftware\MagentoAdapter\Api\Data;

interface AttachmentInterface
{

  /**
   * @return string
   */
  public function getFileType();

  /**
   * @return string
   */
  public function getFileName();

  /**
   * @return string
   */
  public function getContent();

  /**
   * @return string
   */
  public function getDisposition();

  /**
   * @return string
   */
  public function getEncoding();

  /**
   * @return void
   */
  public function setFileType($fileType);

  /**
   * @return void
   */
  public function setFileName($fileName);

  /**
   * @return void
   */
  public function setContent($content);

  /**
   * @return void
   */
  public function setDisposition($disposition);

  /**
   * @return void
   */
  public function setEncoding($encoding);
}
