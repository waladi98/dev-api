<?php

    namespace App\Models;

    use Illuminate\Http\Request;
    use Illuminate\Database\Eloquent\Model;
    use DB;

    class CoreModel extends Model
    { 
        // public $db_name      = NULL;
        // public $db_prefix    = NULL;
        // public $entity_name  = NULL;
        // public $table_prefix = NULL;
        // public $table_type   = NULL;
        // public $key_field    = NULL;

        protected function getData($table_name,$request)
        { 
            
            $select = $request['select'];
            $where = $request['where'];
            $group = $request['group'];
            $order = $request['order'];
            $limit = $request['limit'];
            $offset = $request['offset'];
            $query  = DB::table($table_name);    
            $query->selectRaw($select);
            $query->whereRaw($where);
            //$query->groupByRaw($group);
            $query->orderByRaw($order);
            $query->take($limit);
            $query->offset($offset);
            $data = array();
            $data = $query->get();  
            return $data;
        }

        protected function create($table_name,$arg_data)
        {
            return DB::table($table_name)->insert($arg_data);
        }

        protected function modify($table_name,$arg_data,$arg_where)
        {
            
            return DB::table($table_name)
                       ->whereRaw($arg_where)
                       ->update($arg_data);
        }
        protected function remove($table_name,$arg_where)
        {
           
            return  DB::table($table_name)->whereRaw($arg_where)->delete();
        }
        /*cek kolom pada tabel dari sv_rule_validations*/
        protected function initColumnForInput(Request $request,$arg_db,$arg_table)
        {
            
            $res = DB::select(" SELECT COLUMN_NAME as COLUMN_NAME
                                FROM   INFORMATION_SCHEMA.COLUMNS
                                WHERE  TABLE_SCHEMA = '$arg_db' AND
                                       TABLE_NAME   = '$arg_table'
                                ORDER  BY ORDINAL_POSITION");

            if (!empty($res))
            {
                $input = [];

                foreach ($res as $row) 
                {
                    if (!empty($request->input($row->COLUMN_NAME)))
                    {
                        $input[$row->COLUMN_NAME] = $request->input($row->COLUMN_NAME);
                    }
                }        

                return $input;
            }
            else
            {
                return [];
            }
        }
        /*cek rules kolom pada tabel dari sv_rule_validations*/
        protected function initColumnForRules($arg_db,$arg_table,$action)
        {
            $res = DB::select(" SELECT COLUMN_NAME    as COLUMN_NAME,
                                       COLUMN_KEY     as column_key,
                                       COLUMN_COMMENT as komentar,
                                       EXTRA          as extra,
                                       required_rule,
                                       exists_rule
                                FROM   $arg_db.sv_rules_definition
                                WHERE  TABLE_SCHEMA = '$arg_db' AND
                                       TABLE_NAME   = '$arg_table' AND
                                       COLUMN_DEFAULT IS NULL AND
                                       (IS_NULLABLE = 'NO' OR COLUMN_KEY = 'MUL')
                                ORDER  BY ORDINAL_POSITION");

            if (!empty($res))
            {
                $rules = [];

                foreach ($res as $row) 
                {
                    if ($row->komentar != "BY_SYSTEM" AND $row->extra != "auto_increment")
                    {
                        if($action == "modify" && $row->column_key != 'PRI')
                        {
                            $rules[$row->COLUMN_NAME] = [$row->exists_rule];
                        }
                        else
                        {
                            $rules[$row->COLUMN_NAME] = [$row->required_rule,$row->exists_rule];
                        }
                    }
                }        

                return $rules;
            }
            else
            {
                return [];
            }
        }
        protected function initPrefix($arg_modul)
        {
            $res = DB::select(" SELECT db_prefix
                                FROM   smart_sys_aplikasi
                                WHERE  aplikasi = '$arg_modul'");

            if (!empty($res))
            {
                return $res[0]->db_prefix;
            }
            else
            {
                return false;
            }
        }

        protected function getJumlah($table_name,$where,$group)
        {
            
            $res =  DB::table($table_name)
                    ->selectRaw("COUNT(0) as jumlah")
                    ->when
                        ($where,function ($query, $where) 
                                {
                                    return $query->whereRaw($where);
                                }
                        )                    
                    ->when
                        ($group,function ($query, $group) 
                                {
                                    return $query->groupByRaw($group);
                                }
                        )                    
                    ->get();
                    
            return $res[0]->jumlah;
        }

    }