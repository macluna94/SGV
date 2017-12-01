<?php header('Content-Type: text/html; charset=UTF-8');

include "php/connection.php";




    $options_hubs="";
    	$query_hubs = $connection->query("SELECT id, caption FROM hubs");
    	$hubs = '<option value="0"> Elige una marca</option>';
    	while($fila = $query_hubs->fetch_array()){
    	$options_hubs.='<option value="'.$fila["id"].'">'.$fila["caption"].'</option>';
    	}




    $options_responsable = "";
    $query_responsable = $connection->query('SELECT users.id, users.`name` FROM users WHERE NOT deleted');

    while ($l_user = $query_responsable->fetch_array()) {
        $options_responsable.= '<option value="'.$l_user["id"].'">'.$l_user["name"].'</option>';
    }


$option_state = "";
$query_state = $connection->query('SELECT id, `name` FROM states ORDER BY `name`;');
while ($q_stte = $query_state->fetch_array()) {
    $option_state.= '<option value="'.$q_stte['id'].'">'.$q_stte['name'].'</option>';
}


$query_request = "SELECT
        requests.*, drivers. NAME AS driver_name,
        responsables. NAME AS responsable_name,
        hubs.caption AS labor_cap
        FROM
        requests
        LEFT JOIN users AS drivers ON drivers.id = requests.driver
        INNER JOIN users AS responsables ON responsables.id = requests.responsable
        INNER JOIN hubs ON hubs.id = requests.hub
        WHERE
        requests.deleted = 0";
$query_perm = "SELECT\n".
        "permissions.app_id,\n".
        "permissions.id\n".
        "FROM\n".
        "    permissions\n".
        "INNER JOIN group_perms ON group_perms.permission = permissions.id\n".
        "INNER JOIN groups ON groups.id = group_perms.`group`\n".
        "INNER JOIN user_groups ON user_groups.`group` = groups.id\n".
        "WHERE\n".
        "    user_groups.`user` = $id\n".
        "AND permissions.app_id LIKE 'requests%'";
$sql_viewrequest_all = 
    "SELECT\n".
    "requests_status.*,\n".
    "requests.*,\n".
    "drivers.`name` AS driver_name,\n".
    "responsables.`name` AS responsable_name,\n".
    "CONCAT_WS( \" \", vehicles.economicno, vehiclesbrands.brand, vehiclessubbrands.subbrand ) AS vehicle_cap \n".
    "FROM\n".
    "requests\n".
    "INNER JOIN (\n".
    "SELECT\n".
    "records_status.*,\n".
    "groups.`name` AS next_authorization_group\n".
    "FROM\n".
    "(\n".
    "SELECT\n".
    "records.*,\n".
    "NOT ISNULL( user_authorizations.record ) AS authorized,\n".
    "NOT ISNULL( user_cancellations.record ) AS cancelled,\n".
    "IF ( ISNULL( user_index.`index` ), - 1, user_index.`index` ) AS current_user_index,\n".
    "IF ( ISNULL( last_authorized_indexes.max_authorized_index ), - 1, last_authorized_indexes.max_authorized_index ) AS max_authorized_index,\n".
    "IF ( ISNULL( max_auth_indexes.max_index ), - 1, max_auth_indexes.max_index ) AS max_index,                 \n".
    "authorized_group                   \n".
    "FROM\n".
    "(\n".
    "SELECT\n".
    "log.record,\n".
    "requests.hub,\n".
    "log.`user` AS usercreated,\n".
    "user_groups.`group` AS user_group,\n".
    "users.`name` AS usercreatedname,\n".
    "( log.`user` = $id AND action = 0 ) AS isusercreated,\n".
    "NOT ISNULL( auditors.hubid ) AS isauditor \n".
    "FROM\n".
    "log\n".
    "INNER JOIN requests ON requests.id = log.record\n".
    "INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
    "INNER JOIN users ON users.id = log.`user`\n".
    "LEFT JOIN (\n".
    "SELECT\n".
    "hubid,\n".
    "audited.`user` AS audited,\n".
    "auditors.`user` AS auditor\n".
    "FROM\n".
    "authorizations\n".
    "INNER JOIN user_groups AS auditors ON auditors.`group` = authorizations.`group`\n".
    "INNER JOIN user_groups AS audited ON audited.`group` = authorizations.groupid \n".
    "WHERE\n".
    "auditors.`user` = $id \n".
    ") AS auditors ON auditors.audited = log.`user` \n".
    "AND auditors.hubid = requests.hub \n".
    "WHERE\n".
    "log.module = 1 \n".
    "AND action = 0 \n".
    "GROUP BY\n".
    "log.record\n".
    ") AS records\n".
    "LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.`user` = $id AND log.action = 3 ) AS user_authorizations ON user_authorizations.record = records.record\n".
    "LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.action = 5 ) AS user_cancellations ON user_cancellations.record = records.record\n".
    "LEFT JOIN (\n".
    "SELECT\n".
    "user_groups.`user`,\n".
    "auth.hubid,\n".
    "auth.`index` AS max_index\n".
    "FROM\n".
    "user_groups\n".
    "INNER JOIN (\n".
    "SELECT\n".
    "authorizations.groupid,\n".
    "authorizations.hubid,\n".
    "Max( authorizations.`index` ) AS `index`\n".
    "FROM\n".
    "authorizations\n".
    "GROUP BY\n".
    "authorizations.groupid,\n".
    "authorizations.hubid\n".
    ") AS auth ON auth.groupid = user_groups.`group`\n".
    ") AS max_auth_indexes ON max_auth_indexes.`user` = records.usercreated AND max_auth_indexes.hubid = records.hub\n".
    "LEFT JOIN (\n".
    "SELECT\n".
    "user_groups.`user`,\n".
    "authorizations.`index`,\n".
    "authorizations.hubid\n".
    "FROM\n".
    "authorizations\n".
    "INNER JOIN user_groups ON user_groups.`group` = authorizations.groupid\n".
    "INNER JOIN user_groups AS ug ON ug.`group` = authorizations.`group`\n".
    "WHERE\n".
    "ug.`user` = $id\n".
    ") AS user_index ON user_index.`user` = records.usercreated AND user_index.hubid = records.hub\n".
    "LEFT JOIN (\n".
    "SELECT\n".
    "user_approver.record,\n".
    "MAX( authorizations.`index` ) AS max_authorized_index,\n".
    "groups.`name` AS authorized_group\n".
    "FROM\n".
    "(                                  \n".
    "SELECT\n".
    "log.record,\n".
    "user_groups.`group` AS usergroup,\n".
    "approvers.approvergroup\n".
    "FROM\n".
    "log\n".
    "INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
    "LEFT JOIN (\n".
    "SELECT\n".
    "log.record,\n".
    "log.`user` AS approver,\n".
    "user_groups.`group` AS approvergroup\n".
    "FROM\n".
    "log\n".
    "INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
    "WHERE\n".
    "log.module = 1\n".
    "AND log.action = 3\n".
    ") AS approvers ON approvers.record = log.record\n".
    "WHERE\n".
    "log.module = 1\n".
    "AND log.action = 0\n".
    ") AS user_approver\n".
    "INNER JOIN requests ON requests.id = user_approver.record\n".
    "INNER JOIN authorizations ON authorizations.groupid = user_approver.usergroup\n".
    "INNER JOIN groups ON groups.id = user_approver.approvergroup\n".
    "AND authorizations.`group` = user_approver.approvergroup \n".
    "AND requests.hub = authorizations.hubid \n".
    "GROUP BY\n".
    "user_approver.record\n".
    ") AS last_authorized_indexes ON last_authorized_indexes.record = records.record\n".
    ") AS records_status\n".
    "LEFT JOIN authorizations ON authorizations.hubid = records_status.hub AND authorizations.groupid = records_status.user_group AND authorizations.`index` = records_status.max_authorized_index + 1\n".
    "LEFT JOIN groups ON groups.id = authorizations.`group`\n".
    ") AS requests_status ON requests_status.record = requests.id\n".
    "LEFT JOIN users AS drivers ON drivers.id = requests.driver\n".
    "INNER JOIN users AS responsables ON responsables.id = requests.responsable\n".
    "INNER JOIN vehicles ON vehicles.id = requests.vehicle\n".
    "INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand\n".
    "INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type \n".
    "WHERE\n".
    "NOT requests.deleted \n".
    "ORDER BY\n".
    "id DESC;";
$sql_viewrequest = 
    "SELECT\n".
    "    requests_status.*,\n".
    "    requests.*,\n".
    "    drivers.`name` AS driver_name,\n".
    "    responsables.`name` AS responsable_name,\n".
    "    CONCAT_WS( \" \", vehicles.economicno, vehiclesbrands.brand, vehiclessubbrands.subbrand ) AS vehicle_cap \n".
    "FROM\n".
    "    requests\n".
    "    INNER JOIN (\n".
    "                SELECT\n".
    "                    records_status.*,\n".
    "                    groups.`name` AS next_authorization_group\n".
    "                FROM\n".
    "                    (\n".
    "                        SELECT\n".
    "                                records.*,\n".
    "                                NOT ISNULL( user_authorizations.record ) AS authorized,\n".
    "                                NOT ISNULL( user_cancellations.record ) AS cancelled,\n".
    "                                IF ( ISNULL( user_index.`index` ), - 1, user_index.`index` ) AS current_user_index,\n".
    "                                IF ( ISNULL( last_authorized_indexes.max_authorized_index ), - 1, last_authorized_indexes.max_authorized_index ) AS max_authorized_index,\n".
    "                                IF ( ISNULL( max_auth_indexes.max_index ), - 1, max_auth_indexes.max_index ) AS max_index,                    \n".
    "                                authorized_group                    \n".
    "                            FROM\n".
    "                                (\n".
    "                                    SELECT\n".
    "                                        log.record,\n".
    "                                        requests.hub,\n".
    "                                        log.`user` AS usercreated,\n".
    "                                        user_groups.`group` AS user_group,\n".
    "                                        users.`name` AS usercreatedname,\n".
    "                                        ( log.`user` = $id AND action = 0 ) AS isusercreated,\n".
    "                                        NOT ISNULL( auditors.hubid ) AS isauditor \n".
    "                                    FROM\n".
    "                                        log\n".
    "                                        INNER JOIN requests ON requests.id = log.record\n".
    "                                        INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
    "                                        INNER JOIN users ON users.id = log.`user`\n".
    "                                        LEFT JOIN (\n".
    "                                            SELECT\n".
    "                                                hubid,\n".
    "                                                audited.`user` AS audited,\n".
    "                                                auditors.`user` AS auditor\n".
    "                                            FROM\n".
    "                                                authorizations\n".
    "                                                INNER JOIN user_groups AS auditors ON auditors.`group` = authorizations.`group`\n".
    "                                                INNER JOIN user_groups AS audited ON audited.`group` = authorizations.groupid \n".
    "                                            WHERE\n".
    "                                                auditors.`user` = $id \n".
    "                                        ) AS auditors ON auditors.audited = log.`user` \n".
    "                                        AND auditors.hubid = requests.hub \n".
    "                                    WHERE\n".
    "                                        log.module = 1 \n".
    "                                        AND action = 0 \n".
    "                                        AND (log.`user` = $id OR auditors.auditor = $id)\n".
    "                                    GROUP BY\n".
    "                                        log.record\n".
    "                                ) AS records\n".
    "                                LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.`user` = $id AND log.action = 3 ) AS user_authorizations ON user_authorizations.record = records.record\n".
    "                                LEFT JOIN ( SELECT record FROM log WHERE log.module = 1 AND log.action = 5 ) AS user_cancellations ON user_cancellations.record = records.record\n".
    "                                LEFT JOIN (\n".
    "                                            SELECT\n".
    "                                                user_groups.`user`,\n".
    "                                                auth.hubid,\n".
    "                                                auth.`index` AS max_index\n".
    "                                            FROM\n".
    "                                                user_groups\n".
    "                                                INNER JOIN (\n".
    "                                            SELECT\n".
    "                                                authorizations.groupid,\n".
    "                                                authorizations.hubid,\n".
    "                                                Max( authorizations.`index` ) AS `index`\n".
    "                                            FROM\n".
    "                                                authorizations\n".
    "                                            GROUP BY\n".
    "                                                authorizations.groupid,\n".
    "                                                authorizations.hubid\n".
    "                                                ) AS auth ON auth.groupid = user_groups.`group`\n".
    "                                ) AS max_auth_indexes ON max_auth_indexes.`user` = records.usercreated AND max_auth_indexes.hubid = records.hub\n".
    "                                LEFT JOIN (\n".
    "                                            SELECT\n".
    "                                                user_groups.`user`,\n".
    "                                                authorizations.`index`,\n".
    "                                                authorizations.hubid\n".
    "                                            FROM\n".
    "                                                authorizations\n".
    "                                                INNER JOIN user_groups ON user_groups.`group` = authorizations.groupid\n".
    "                                                INNER JOIN user_groups AS ug ON ug.`group` = authorizations.`group`\n".
    "                                            WHERE\n".
    "                                                ug.`user` = $id\n".
    "                                ) AS user_index ON user_index.`user` = records.usercreated AND user_index.hubid = records.hub\n".
    "                                LEFT JOIN (\n".
    "                                            SELECT\n".
    "                                                user_approver.record,\n".
    "                                                MAX( authorizations.`index` ) AS max_authorized_index,\n".
    "                                                groups.`name` AS authorized_group\n".
    "                                            FROM\n".
    "                                                (                                    \n".
    "                                                    SELECT\n".
    "                                                        log.record,\n".
    "                                                        user_groups.`group` AS usergroup,\n".
    "                                                        approvers.approvergroup\n".
    "                                                    FROM\n".
    "                                                        log\n".
    "                                                        INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
    "                                                        LEFT JOIN (\n".
    "                                                                    SELECT\n".
    "                                                                        log.record,\n".
    "                                                                        log.`user` AS approver,\n".
    "                                                                        user_groups.`group` AS approvergroup\n".
    "                                                                    FROM\n".
    "                                                                        log\n".
    "                                                                        INNER JOIN user_groups ON user_groups.`user` = log.`user`\n".
    "                                                                    WHERE\n".
    "                                                                        log.module = 1\n".
    "                                                                        AND log.action = 3\n".
    "                                                        ) AS approvers ON approvers.record = log.record\n".
    "                                                    WHERE\n".
    "                                                        log.module = 1\n".
    "                                                        AND log.action = 0\n".
    "                                                ) AS user_approver\n".
    "                                                INNER JOIN requests ON requests.id = user_approver.record\n".
    "                                                INNER JOIN authorizations ON authorizations.groupid = user_approver.usergroup\n".
    "                                                INNER JOIN groups ON groups.id = user_approver.approvergroup\n".
    "                                                AND authorizations.`group` = user_approver.approvergroup \n".
    "                                                AND requests.hub = authorizations.hubid \n".
    "                                            GROUP BY\n".
    "                                                user_approver.record\n".
    "                                ) AS last_authorized_indexes ON last_authorized_indexes.record = records.record\n".
    "                    ) AS records_status\n".
    "                    LEFT JOIN authorizations ON authorizations.hubid = records_status.hub AND authorizations.groupid = records_status.user_group AND authorizations.`index` = records_status.max_authorized_index + 1\n".
    "                    LEFT JOIN groups ON groups.id = authorizations.`group`\n".
    "    ) AS requests_status ON requests_status.record = requests.id\n".
    "    LEFT JOIN users AS drivers ON drivers.id = requests.driver\n".
    "    INNER JOIN users AS responsables ON responsables.id = requests.responsable\n".
    "    INNER JOIN vehicles ON vehicles.id = requests.vehicle\n".
    "    INNER JOIN vehiclesbrands ON vehiclesbrands.id = vehicles.brand\n".
    "    INNER JOIN vehiclessubbrands ON vehiclessubbrands.id = vehicles.type \n".
    "WHERE\n".
    "    NOT requests.deleted \n".
    "ORDER BY\n".
    "    id DESC;";

$option_items = "";
$query_items = $connection->query("SELECT * FROM items ORDER BY caption");
while ($x_tte = $query_items->fetch_array()) {
    $option_items.= '<option value="'.$x_tte['caption'].'">'.$x_tte['caption'].'</option>';
}


?>
