<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

    class CommentsController extends ManagementAppController
    {
        var $name = 'Comments';

        var $helpers = array(
            'Text',
            'Filter.Filter'
        );

        var $uses = array( 'Core.Comment' );

        function admin_index()
        {
            $this->paginate = array(
                'fields' => array(
                    'Comment.id',
                    'Comment.class',
                    'Comment.name',
                    'Comment.email',
                    'Comment.website',
                    'Comment.comment',
                    'Comment.active',
                    'Comment.foreign_id',
                    'Comment.created',
                ),
                'order' => array(
                    'Comment.active' => 'ASC',
                    'Comment.created' => 'ASC',
                ),
                'limit' => 20
            );

            $comments = $this->paginate( 'Comment', $this->Filter->filter );
            $this->set( 'filterOptions', $this->Filter->filterOptions );

            pr( $this->Filter->filterOptions );

            $this->set( compact( 'comments' ) );
        }

        function admin_perge( $date = null )
        {
            if ( !$date )
            {
                $date = date( 'Y-m-d h:i:s', mktime( 0, 0, 0, date( 'm' ) -1, date( 'd' ), date( 'y' ) ) );
            }

            $old = $this->Comment->find(
                'list',
                array(
                    'fields' => array(
                        'Comment.id',
                        'Comment.id'
                    ),
                    'conditions' => array(
                        'Comment.created < ' => $date,
                        'Comment.active' => 0
                    ),
                    'contain' => false
                )
            );

            if ( empty( $old ) )
            {
                $this->Session->setFlash( __( 'No old comments found', true ) );
                $this->redirect( $this->referer() );
            }

            $i = 0;
            foreach( $old as $id )
            {
                if ( $this->Comment->delete( $id ) )
                {
                    $i++;
                }
            }

            $this->Session->setFlash( sprintf( '%s %s', $i, __( 'Comments deleted', true ) ) );
        }
    }
?>