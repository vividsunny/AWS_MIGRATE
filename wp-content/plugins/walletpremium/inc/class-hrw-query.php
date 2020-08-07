<?php

/*
 * Query
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! class_exists( 'HRW_Query' ) ) {

    /**
     * Class Query
     */
    class HRW_Query {

        /**
         * Table
         */
        protected $table = '' ;

        /**
         * Database object
         */
        protected $database = null ;

        /**
         * Type
         */
        protected $type = 0 ;

        /**
         * Target
         */
        protected $target = null ;

        /**
         * Joins 
         */
        protected $joins = array() ;

        /**
         * Where 
         */
        protected $where = array() ;

        /**
         * GROUP BY 
         */
        protected $group_by = array() ;

        /**
         *  HAVING
         */
        protected $having = array() ;

        /**
         * Number of rows returned by the SELECT 
         */
        protected $limit = 0 ;

        /**
         * Specify the offset of the first row to return 
         */
        protected $offset = 0 ;

        /**
         *  Order By 
         */
        protected $order_by = null ;

        /**
         * Order
         */
        protected $order = 'ASC' ;

        /**
         * Index By 
         */
        protected $index_by = null ;

        /**
         * alias
         */
        protected $alias ;

        /**
         * Constructor.
         */
        public function __construct( $table , $alias = 't' ) {

            if ( ! $this->database ) {
                global $wpdb ;

                $this->database = $wpdb ;
            }

            $this->table    = $table ;
            $this->alias    = $alias ;
            $this->target   = "`{$alias}`.*" ;
            $this->order_by = "`{$alias}`.`id`" ;
        }

        /**
         * Set query type to SELECT and specify fields to be selected.
         */
        public function select( $target = null ) {
            $this->type   = 0 ;
            $this->target = $target !== null ? $target : "`{$this->alias}`.*" ;

            return $this ;
        }

        /**
         * Left join another table.
         */
        public function leftJoin( $table , $alias , $on ) {

            $this->joins[ $alias ] = array(
                'table' => $table ,
                'on'    => $on ,
                'type'  => 'LEFT'
                    ) ;

            return $this ;
        }

        /**
         * Left join no table.
         */
        public function tableJoin( $table , $alias , $on ) {
            $this->joins[ $alias ] = array(
                'table' => $table ,
                'on'    => $on ,
                'type'  => 'LEFT'
                    ) ;

            return $this ;
        }

        /**
         * Inner join another table.
         */
        public function innerJoin( $table , $alias , $on ) {

            $this->joins[ $alias ] = array(
                'table' => $table ,
                'on'    => $on ,
                'type'  => 'INNER'
                    ) ;

            return $this ;
        }

        /**
         * Set the maximum number of results to return at once.
         */
        public function limit( $limit ) {
            $this->limit = ( int ) $limit ;

            return $this ;
        }

        /**
         * Set the offset to use when calculating results.
         */
        public function offset( $offset ) {
            $this->offset = ( int ) $offset ;

            return $this ;
        }

        /**
         * Set the column we should sort by.
         */
        public function orderBy( $order_by ) {
            $this->order_by = $order_by ;

            return $this ;
        }

        /**
         * Set the order we should sort by.
         */
        public function order( $order ) {
            $this->order = $order ;

            return $this ;
        }

        /**
         * Add a `=` clause to the search query.
         */
        public function where( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'where' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `!=` clause to the search query.
         */
        public function whereNot( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'not' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `LIKE` clause to the search query.
         */
        public function whereLike( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'like' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `NOT LIKE` clause to the search query.
         */
        public function whereNotLike( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'not_like' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `<` clause to the search query.
         */
        public function whereLt( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'lt' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `<=` clause to the search query.
         */
        public function whereLte( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'lte' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `>` clause to the search query.
         */
        public function whereGt( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'gt' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `>=` clause to the search query.
         */
        public function whereGte( $column , $value , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'gte' , 'column' => $column , 'value' => $value , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add an `IN` clause to the search query.
         */
        public function whereIn( $column , array $in , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'in' , 'column' => $column , 'value' => $in , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a `NOT IN` clause to the search query.
         */
        public function whereNotIn( $column , array $not_in , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'not_in' , 'column' => $column , 'value' => $not_in , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add an OR statement to the where clause.
         */
        public function whereAny( array $where , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'any' , 'where' => $where , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add an AND statement to the where clause
         */
        public function whereAll( array $where , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'all' , 'where' => $where , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Add a BETWEEN statement to the where clause.
         */
        public function whereBetween( $column , $start , $end , $glue = 'AND' ) {
            $this->where[] = array( 'type' => 'between' , 'column' => $column , 'start' => $start , 'end' => $end , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Set the group by.
         */
        public function groupBy( $column ) {
            $this->group_by[] = $column ;

            return $this ;
        }

        /**
         * Add raw having statement.
         */
        public function havingRaw( $statement , array $values , $glue = 'AND' ) {
            $this->having[] = array( 'type' => 'raw_having' , 'statement' => $statement , 'values' => $values , 'glue' => $glue ) ;

            return $this ;
        }

        /**
         * Set a column that will be used as index for resulting array.
         */
        public function indexBy( $column ) {
            $this->index_by = $column ;

            return $this ;
        }

        /**
         * Runs the same query as find, but with no limit and don't retrieve the
         * results, just the total items found.
         */
        public function count() {

            return ( int ) $this->database->get_var( $this->composeQuery( true ) ) ;
        }

        /**
         * Returns the specified column
         */
        public function fetchCol( $column ) {

            $this->select( $column ) ;

            return $this->database->get_col( $this->composeQuery() ) ;
        }

        /**
         * Execute query and hydrate result as array.
         */
        public function fetchArray() {
            // Query
            $query = $this->composeQuery( false ) ;

            return $this->database->get_results( $query , ARRAY_A ) ;
        }

        /**
         * Execute query and hydrate result as object.
         */
        public function fetchObject() {
            // Query
            $query = $this->composeQuery( false ) ;

            return $this->database->get_results( $query , OBJECT ) ;
        }

        /**
         * Execute query and fetch one result as array.
         */
        public function fetchRow() {

            return $this->database->get_row( $this->composeQuery( false ) , ARRAY_A ) ;
        }

        /**
         * Compose the actual SQL query from all of our filters and options.
         */
        public function composeQuery( $only_count = false ) {
            $table  = $this->table ;
            $join   = '' ;
            $where  = '' ;
            $group  = '' ;
            $having = '' ;
            $order  = '' ;
            $limit  = '' ;
            $offset = '' ;
            $values = array() ;

            // Join.
            foreach ( $this->joins as $alias => $t ) {
                $join .= " {$t[ 'type' ]} JOIN `{$t[ 'table' ]}` AS `{$alias}` ON {$t[ 'on' ]}" ;
            }

            // Where
            foreach ( $this->where as $q ) {
                // where
                if ( $q[ 'type' ] == 'where' ) {
                    $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} " ;
                    if ( $q[ 'value' ] === null ) {
                        $where .= 'IS NULL' ;
                    } else {
                        $where .= "= '{$q[ 'value' ]}'" ;
                    }
                } // where_not
                elseif ( $q[ 'type' ] == 'not' ) {
                    $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} " ;
                    if ( $q[ 'value' ] === null ) {
                        $where .= 'IS NOT NULL' ;
                    } else {
                        $where .= "!= '{$q[ 'value' ]}'" ;
                    }
                } // where_like
                elseif ( $q[ 'type' ] == 'like' ) {
                    if ( hrw_check_is_array( $q[ 'column' ] ) ) {
                        $where .= " {$q[ 'glue' ]} (" ;
                        foreach ( $q[ 'column' ] as $column => $value ) {
                            $where .= " ({$column} LIKE '{$value}') {$q[ 'value' ]}" ;
                        }
                        $where = substr( $where , 0 , - 3 ) . ')' ;
                    } else {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} LIKE '{$q[ 'value' ]}'" ;
                    }
                } // where_not_like
                elseif ( $q[ 'type' ] == 'not_like' ) {
                    if ( hrw_check_is_array( $q[ 'column' ] ) ) {
                        $where .= " {$q[ 'glue' ]} (" ;
                        foreach ( $q[ 'column' ] as $column => $value ) {
                            $where .= " ({$column} NOT LIKE '{$value}') {$q[ 'value' ]}" ;
                        }
                        $where = substr( $where , 0 , - 3 ) . ')' ;
                    } else {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} NOT LIKE '{$q[ 'value' ]}'" ;
                    }
                } // where_lt
                elseif ( $q[ 'type' ] == 'lt' ) {
                    if ( hrw_check_is_array( $q[ 'column' ] ) ) {
                        $where .= " {$q[ 'glue' ]} (" ;
                        foreach ( $q[ 'column' ] as $column => $value ) {
                            $where .= " ({$column} < '{$value}') {$q[ 'value' ]}" ;
                        }
                        $where = substr( $where , 0 , - 3 ) . ')' ;
                    } else {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} < '{$q[ 'value' ]}'" ;
                    }
                } // where_lte
                elseif ( $q[ 'type' ] == 'lte' ) {
                    if ( hrw_check_is_array( $q[ 'column' ] ) ) {
                        $where .= " {$q[ 'glue' ]} (" ;
                        foreach ( $q[ 'column' ] as $column => $value ) {
                            $where .= " ({$column} <= '{$value}') {$q[ 'value' ]}" ;
                        }
                        $where = substr( $where , 0 , - 3 ) . ')' ;
                    } else {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} <= '{$q[ 'value' ]}'" ;
                    }
                } // where_gt
                elseif ( $q[ 'type' ] == 'gt' ) {
                    if ( hrw_check_is_array( $q[ 'column' ] ) ) {
                        $where .= " {$q[ 'glue' ]} (" ;
                        foreach ( $q[ 'column' ] as $column => $value ) {
                            $where .= " ({$column} > '{$value}') {$q[ 'value' ]}" ;
                        }
                        $where = substr( $where , 0 , - 3 ) . ')' ;
                    } else {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} > '{$q[ 'value' ]}'" ;
                    }
                } // where_gte
                elseif ( $q[ 'type' ] == 'gte' ) {
                    if ( hrw_check_is_array( $q[ 'column' ] ) ) {
                        $where .= " {$q[ 'glue' ]} (" ;
                        foreach ( $q[ 'column' ] as $column => $value ) {
                            $where .= " ({$column} >= '{$value}') {$q[ 'value' ]}" ;
                        }
                        $where = substr( $where , 0 , - 3 ) . ')' ;
                    } else {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} >= '{$q[ 'value' ]}'" ;
                    }
                } // where_in
                elseif ( $q[ 'type' ] == 'in' ) {
                    $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} IN (" ;

                    if ( empty( $q[ 'value' ] ) ) {
                        $where .= 'NULL' ;
                    } else {
                        foreach ( $q[ 'value' ] as $value ) {
                            $where .= "'{$value}'," ;
                        }
                        $where = substr( $where , 0 , - 1 ) ;
                    }

                    $where .= ')' ;
                } // where_not_in
                elseif ( $q[ 'type' ] == 'not_in' ) {
                    if ( ! empty( $q[ 'value' ] ) ) {
                        $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} NOT IN (" ;

                        foreach ( $q[ 'value' ] as $value ) {
                            $where .= "{$value}," ;
                        }

                        $where = substr( $where , 0 , - 1 ) . ')' ;
                    }
                } // where_any
                elseif ( $q[ 'type' ] == 'any' ) {
                    $where .= " {$q[ 'glue' ]} (" ;

                    foreach ( $q[ 'where' ] as $column => $value ) {
                        $where .= "{$column} = {$value} OR " ;
                    }

                    $where = substr( $where , 0 , - 4 ) . ')' ;
                } // where_all
                elseif ( $q[ 'type' ] == 'all' ) {
                    $where .= " {$q[ 'glue' ]} (" ;

                    foreach ( $q[ 'where' ] as $column => $value ) {
                        $where .= "{$column} = {$value} AND " ;
                    }

                    $where = substr( $where , 0 , - 5 ) . ')' ;
                } // between
                elseif ( $q[ 'type' ] == 'between' ) {
                    $where .= " {$q[ 'glue' ]} {$q[ 'column' ]} BETWEEN '{$q[ 'start' ]}' AND '{$q[ 'end' ]}'" ;
                }
            }

            // Finish where clause
            if ( $where != '' ) {
                $where = ' WHERE ' . substr( $where , strpos( $where , ' ' , 1 ) + 1 ) ;
            }

            // Group
            if ( ! empty( $this->group_by ) ) {
                $group = ' GROUP BY ' . implode( ',' , $this->group_by ) ;
            }

            // Having
            foreach ( $this->having as $q ) {
                // raw_having
                if ( $q[ 'type' ] == 'raw_having' ) {
                    $having .= " {$q[ 'glue' ]} ({$q[ 'statement' ]})" ;
                    foreach ( $q[ 'values' ] as $value ) {
                        $values[] = $value ;
                    }
                }
            }

            // Finish having clause
            if ( ! empty( $having ) ) {
                $having = ' HAVING ' . substr( $having , strpos( $having , ' ' , 1 ) + 1 ) ;
            }

            // Order
            $order = ' ORDER BY ' . $this->order_by . ' ' . $this->order ;

            // Limit
            if ( $this->limit > 0 ) {
                $limit = ' LIMIT ' . $this->limit ;
            }

            // Offset
            if ( $this->offset > 0 ) {
                $offset = ' OFFSET ' . $this->offset ;
            }

            // Query
            if ( $only_count ) {
                return "SELECT COUNT(*) FROM `{$table}` AS `{$this->alias}`{$join}{$where}{$group}{$having}" ;
            }

            return "SELECT {$this->target} FROM `{$table}` AS `{$this->alias}`{$join}{$where}{$group}{$having}{$order}{$limit}{$offset}" ;
        }

    }

}