<?php
namespace Tendoo\Core\Exceptions;
use Exception;

class InvalidModuleException 
{
    public function __construct( $message = null )
    {
        parent::__construct();
        $this->title    =   __( 'Invalid Module' );
        $this->message  =   ( $message == null ) ? __( 'The file you\'ve submitted is not a valid Tendoo module.' ) : $message;
    }

    /**
     * get Title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}