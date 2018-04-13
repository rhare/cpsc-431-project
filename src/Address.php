<?php
class Address
{
    // Instance attributes
    private $name   = array('FIRST'=>"", 'LAST'=>null); 
    private $street = "";
    private $city   = "";
    private $state  = "";
    private $zip    = "";  
    private $country    = "";  

    // Operations

    // name() prototypes:
    //   string name()                          returns name in "Last, First" format.
    //                                          If no first name assigned, then return in "Last" format.
    //                                         
    //   void name(string $value)               set object's $name attribute in "Last, First" 
    //                                          or "Last" format.
    //                                         
    //   void name(array $value)                set object's $name attribute in [first, last] format
    //
    //   void name(string $first, string $last) set object's $name attribute
    function name() 
    {
        // string name()
        if( func_num_args() == 0 )
        {
            if( empty($this->name['FIRST']) ) 
                return $this->name['LAST'];
            else
                return $this->name['LAST'].', '.$this->name['FIRST']; 
        }

        // void name($value)
        else if( func_num_args() == 1 )
        {
            $value = func_get_arg(0);

            if( is_string($value) ) 
            {
                $value = explode(',', $value); // convert string to array 

                if ( count($value) >= 2 ) 
                    $this->name['FIRST'] = htmlspecialchars(trim($value[1]));
                else
                    $this->name['FIRST'] = '';
                $this->name['LAST']  = htmlspecialchars(trim($value[0]));
            }
            else if( is_array ($value) )
            {
                if ( count($value) >= 2 ) 
                    $this->name['LAST'] = htmlspecialchars(trim($value[1]));
                else
                    $this->name['LAST'] = '';
                $this->name['FIRST']  = htmlspecialchars(trim($value[0])); 
            }
        }

        // void name($first_name, $last_name)
        else if( func_num_args() == 2 )
        {
            $this->name['FIRST'] = htmlspecialchars(trim(func_get_arg(0)));
            $this->name['LAST']  = htmlspecialchars(trim(func_get_arg(1))); 
        }

        return $this;
    }

    // street() prototypes:
    //   string street()                          returns street
    //
    //   void street(string $value)               set object's $street attribute 
    //                                                 in "minutes:seconds" format.
    function street() 
    {
        // string street()
        if( func_num_args() == 0 )
        {
            return $this->street;
        }
        // void street($value)
        else if( func_num_args() == 1 )
        {
            $value = func_get_arg(0);

            if( is_string($value) )
                $this->street = $value;
        }

        return $this;
    }

    // city() prototypes:
    //   int city()               returns city
    //                                         
    //   void city(int $value)    set object's $city attribute
    function city() 
    {  
        // int city()
        if( func_num_args() == 0 )
        {
            return $this->city;
        }

        // void city($value)
        else if( func_num_args() == 1 )
        {
            $value = func_get_arg(0);
            if( is_string($value) )
                $this->city = $value;
        }

        return $this;
    }

    // state() prototypes:
    //   int state()            returns the state
    //
    //   void state(int $value) set object's $state attribute
    function state() 
    {
        // int state()
        if( func_num_args() == 0 )
        {
            return $this->state;
        }
        // void state($value)
        else if( func_num_args() == 1 )
        {
            $value = func_get_arg(0);
            if( is_string($value) )
                $this->state = $value;
        }

        return $this;
    }

    // zipcode() prototypes:
    //   int zipcode()               returns the zipcode
    //
    //   void zipcode(int $value)    set object's $zipcode attribute
    function zipcode() 
    {
        // int rebounds()
        if( func_num_args() == 0 )
        {
            return $this->zipcode;
        }
        // void rebounds($value)
        else if( func_num_args() == 1 )
        {
            $value = func_get_arg(0);
            if (is_string($value))
                $this->zipcode = $value;
        }

        return $this;
    }

    // country() prototypes:
    //   string country()               returns the country
    //
    //   void country(string $value)    set object's $country attribute
    function country() 
    {
        // int rebounds()
        if( func_num_args() == 0 )
        {
            return $this->country;
        }
        // void rebounds($value)
        else if( func_num_args() == 1 )
        {
            $value = func_get_arg(0);
            if (is_string($value))
                $this->country = $value;
        }

        return $this;
    }
    function __construct($name="", $street="", $city="", $state="", $zip="", $country="")
    {
        if( is_string($name) )
        {
            // if $name contains at least one tab character, assume all attributes are provided in 
            // a tab separated list.  Otherwise assume $name is just the player's name.
            if( strpos($name, "\t") !== false)
            {
                // assign each argument a value from the tab delineated string respecting relative positions
                list($name, $street, $city, $state, $zip) = explode("\t", $name);
            }
        } 

        // delegate setting attributes so validation logic is applied
        $this->name($name);
        $this->street($street);
        $this->city($city);
        $this->state($state);
        $this->zipcode($zip);
        $this->country($country);
    }

    function __toString()
    {
        return (var_export($this, true));
    }

    // Returns a tab separated value (TSV) string containing the contents of all instance attributes   
    function toTSV()
    {
        return implode("\t", [$this->name(), $this->street(), $this->city(), $this->state(), $this->zipcode()]);
    }

    // Sets instance attributes to the contents of a string containing ordered, tab separated values 
    function fromTSV(string $tsvString)
    {
        // assign each argument a value from the tab delineated string respecting relative positions
        list($name, $street, $city, $state, $zipcode) = explode("\t", $tsvString);
        $this->name($name);
        $this->street($street);
        $this->city($city);
        $this->state($state);
        $this->zipcode($zipcode);
    }

    function toDB($db_conn) {
        $query = "INSERT INTO TeamRoster
                    (Name_Last, Name_First, Street, City, State, Country, ZipCode)
                  VALUES
                    (?, ?, ?, ?, ?, ?, ?)
                  ON DUPLICATE KEY UPDATE 
                    Street = VALUES(Street), 
                    City = VALUES(City), 
                    State = VALUES(State), 
                    Country = VALUES(Country), 
                    ZipCode = VALUES(ZipCode)";

        if(!($stmt = $db_conn->prepare($query))){
            echo "Prepare failed: (" . $db_conn->errno . ") " . $db_conn->error;
            die("Database execution failed");
        }

        // ZipCode has check constarints on it. Will only allow valid zipcode or Null.
        // Cast to NULL if empty.
        $zipcode = (empty($this->zipcode)) ? NULL : $this->zipcode;
        if (!$stmt->bind_param(
          'sssssss', 
          $this->name['LAST'], 
          $this->name['FIRST'], 
          $this->street, 
          $this->city, 
          $this->state, 
          $this->country, 
          $zipcode
        )){
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            die("Database execution failed");
        }
        $stmt->execute();
    }
} // end class PlayerStatistic

?>

