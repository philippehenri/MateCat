<?php
/**
 * Created by PhpStorm.
 * User: fregini
 * Date: 20/12/2016
 * Time: 10:45
 */

namespace Teams;

use PDO ;

class MembershipDao extends \DataAccess_AbstractDao
{

    const TABLE = "teams_users";
    const STRUCT_TYPE = "\\Teams\\MembershipStruct";

    protected static $auto_increment_fields = array('id');
    protected static $primary_keys = array('id');

    public function findById( $id ) {
        $sql = " SELECT * FROM " . self::STRUCT_TYPE . " WHERE id = ? " ;
        $stmt = $this->getConnection()->getConnection()->prepare( $sql ) ;
        $stmt->setFetchMode( PDO::FETCH_CLASS, self::STRUCT_TYPE );
        $stmt->execute( array( $id ) );

        return $stmt->fetch() ;
    }

    /**
     *
     * Find ONE team for the given user. This is to enforce the temporary requirement to
     * have just one team per user.
     *
     * @param \Users_UserStruct $user
     */
    public function findTeambyUser( \Users_UserStruct $user ) {
        $sql = " SELECT teams.* FROM teams JOIN teams_users ON teams_users.id_team = teams.id " .
            " WHERE teams_users.uid = ? " .
            " LIMIT 1 " ;

        $stmt = $this->getConnection()->getConnection()->prepare( $sql ) ;
        $stmt->setFetchMode( PDO::FETCH_CLASS, '\Teams\TeamStruct' );
        $stmt->execute( array( $user->uid ) ) ;

        return static::resultOrNull( $stmt->fetch() );
    }

    protected function _buildResult($array_result)
    {
        // TODO: Implement _buildResult() method.
    }
}