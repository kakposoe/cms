<?php

namespace Tendoo\Core\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;

class TendooHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ( $request->expectsJson() ) {

            if( $exception instanceof HttpException ) {
                return response()->json([
                    'status'    =>  'failed',
                    'message'   =>  __( 'Page Not Found' ),
                    'code'      =>  $exception->getStatusCode()
                ], 401 );
            }

            if( $exception instanceof TokenMismatchException ) {      
                return response()->json([
                    'status'    =>  'failed',
                    'message'   =>  __( 'Token Error Mismatch' ),
                    'code'      =>  'token-error'
                ], 401 );            
            }

            if( $exception instanceof QueryException ) {
                return response()->json([
                    'status'    =>  'failed',
                    'message'   =>  $exception->getMessage(),
                    'code'      =>  'db-error'
                ], 401 );
            }

            if ( $exception instanceof PostTooLargeException ) {
                ob_clean();
                return response()->json([
                    'status'    =>  'failed',
                    'message'   =>  __( 'Unable to process the file. This file is too big.' )
                ], 401 );
            }

            if ( $exception instanceof FileNotFoundException ) {
                return response()->json([
                    'status'    =>  'failed',
                    'message'   =>  sprintf( __( 'Unable find the file : %s.' ), $exception->getMessage() )
                ], 401 );
            }

            if( 
                $exception instanceof ApiAmbiguousTokenException ||
                $exception instanceof ApiForbiddenScopeException ||
                $exception instanceof ApiMissingTokenException || 
                $exception instanceof ApiUnknowEndpointException ||
                $exception instanceof ApiUnknowTokenException ||
                $exception instanceof InvalidModuleException
            ) {
                return response()->json([
                    'status'    =>  'failed',
                    'message'   =>  $exception->getMessage()
                ], 401 );
            }

            if ( $exception instanceof CrudException ) {
                return response()->json( $exception->getResponse() );
            }

            if ( 
                $exception instanceof AccessDeniedException ||
                $exception instanceof RecoveryExpiredException ||
                $exception instanceof FeatureDisabledException
            ) {
                return response()->json([
                    'status'    =>  'danger',
                    'message'   =>  $exception->getMessage()
                ], 401 );
            }
        } else {
            if( $exception instanceof QueryException ) {
                return response()->view( 'tendoo::errors.db-error', [ 'e' => $exception ] );
            } else if ( 
                $exception instanceof AccessDeniedException ||
                $exception instanceof RecoveryExpiredException ||
                $exception instanceof FeatureDisabledException
            ) {
                return response()->view( 'tendoo::errors.common', [ 'e' => $exception ] );
            }
        }
        return parent::render($request, $exception);
    }
}
