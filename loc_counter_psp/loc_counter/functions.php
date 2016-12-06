<?php 
/***********************************************************************************
*   countFunctions
*
*   Function that determines if the line is a physical line with the isPhysicalLine
*   function.
***********************************************************************************/
//*Start countFunctions
function countFunctions()
{
    if (isset($_FILES['file']))
    {
        //Initialize $file variable
        $file = null;

        //Exception handling for file
        try
        {
            $file = fopen($_FILES['file']['tmp_name'], 'r');
        }
        catch (Exception $e)
        {
            die('No file found!');
        }

        //Initialize variables
        $totalLOC = 0;
        $htmlLOC = 0;
        $phpLOC = 0;
        $functionList = array();
        $isPHP = false;
        $isMultComment = false;
        
        //Loop that goes through file line by line
        while (!feof($file))
        {
            //Get the current line of the file
            $line = htmlspecialchars(trim(fgets($file)));
            
            /*
            *   A check to see if the current line is a php tag
            *   if it is, then the program moves into a while 
            *   loop checking each line and adding each line to 
            *   the counter as a php line of code. The loop stops
            *   once it reaches the end of the php block.
            */
            if ($line === htmlspecialchars('<?php'))
            {
                while($line !== htmlspecialchars('?>'))
                {
                    $line = htmlspecialchars(trim(fgets($file)));
                    
                    //Check if line contains special Start tag
                    if (substr($line, 0, 8) === '//*Start')
                    {
                        //Create a variable to store the loc for the function
                        $functionLOC = 0;

                        //Add an element to the associative array and set it to 0
                        $functionName = substr($line, 9);

                        //While loop to add to the loc counter
                        while ($line !== '//*End')
                        {
                            //Move to next line
                            $line = htmlspecialchars(trim(fgets($file)));

                            //Checks if line is a physical line
                            if (isPhysicalLine($line))
                            {
                                $functionLOC++;
                            }
                        }

                        //Add $functionLOC to $totalLOC
                        $phpLOC += $functionLOC;

                        //Add element to $functionList
                        $functionList["$functionName"] = $functionLOC;
                    }
                    else
                    {
                        if (isPhysicalLine($line))
                            $phpLOC++;
                    }
                }
            }
            else 
            {
                if (isPhysicalLine($line) &&
                   strpos("x" . $line, htmlspecialchars('<?php')) != true &&
                   strpos("x" . $line, htmlspecialchars('?>')) != true)
                    $htmlLOC++;
                elseif (isPhysicalLine($line) &&
                    strpos("x" . $line, htmlspecialchars('<?php')) == true &&
                    strpos("x" . $line, htmlspecialchars('?>')) == true)
                    $phpLOC++;
            }
        }
        $totalLOC += $phpLOC;
        $totalLOC += $htmlLOC;
        $functionList['PHP'] = $phpLOC;
        $functionList['HTML'] = $htmlLOC;
        $functionList['Total'] = $totalLOC;
        
        //Close the file
        fclose($file);
        
        //Return the array
        return $functionList;
    }
}
//*End

/***********************************************************************************
*   isPhysicalLine
*
*   This function checks if the current line is a physical line based off of user
*   standards
***********************************************************************************/
//*Start isPhysicalLine
function isPhysicalLine($line)
{
    if ($line === htmlspecialchars('<?php') || 
        $line === htmlspecialchars('?>')    ||
        $line === htmlspecialchars('<!--')  ||
        $line === htmlspecialchars('-->')   ||
        $line === ''  ||
        $line === '{' ||
        $line === '}' ||
        $line === '(' ||
        $line === ')' ||
        substr($line, 0, 1) === '*'     ||
        substr($line, 0, 2) === '//'    ||
        substr($line, 0, 2) === '/*'    ||
        substr($line, 0, 2) === '*/')
    {
        return false;
    }
    else
        return true;
}
//*End
?>