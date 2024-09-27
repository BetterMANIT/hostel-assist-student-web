for Maulana Azad National Institute of Technology, demo edit 3


index.php --> works as a main file
login.php --> works as a backend file


##Database structure : 
Database name : hostel_assist

###Table name : {dmY} + {hostel_name}, Ex : 27092024H10C
----------+---------------+---------+-----------+----------------------------------+------------+-+
| scholar_no  | name          | room_no | photo_url                        | phone_no   | section |
+-------------+---------------+---------+----------------------------------+------------+---------+
| 12345678901 | John Doe      | 304C    | https://example.com/profile.jpg  | 9876543210 | A       |
| 12345678902 | Jane Smith    | 305A    | https://example.com/profile2.jpg | 9876543211 | B       |
| 12345678903 | Alice Johnson | 306B    | https://example.com/profile3.jpg | 9876543212 | C       |
+-------------+---------------+---------+----------------------------------+------------+---------+


###Table name : student_info
----------+---------------+---------+-----------+----------------------------------+------------+---------+-------------+------------------------------+
| scholar_no  | name          | room_no | hostel_name | photo_url                        | phone_no   | section | guardian_no | entry_exit_table_name    |
+-------------+---------------+---------+-----------+----------------------------------+------------+---------+-------------+--------------------------+
| 12345678901 | John Doe      | 304C    | 238HD     | https://example.com/profile.jpg  | 9876543210 | A       | 8765432109  | 27092024H10C             |
| 12345678902 | Jane Smith    | 305A    | 238HD     | https://example.com/profile2.jpg | 9876543211 | B       | 8765432110  | 27092024H10C             |
| 12345678903 | Alice Johnson | 306B    | 239HD     | https://example.com/profile3.jpg | 9876543212 | C       | 8765432111  | 27092024H10C             |
| 23113011109 | Krish Ahirwar | c-304   | NULL      | cooldude69.com                   | 9098998897 | B       | 9099008800  | 27092024H10C             |
+-------------+---------------+---------+-----------+----------------------------------+------------+---------+-------------+--------------------------+

entry_exit_table_name can be empty if the student is in hostel. if he leave for market, then market+{hostelname} table will be assigned. If he leaves for classes the {dmY}+{Hostel} is assigned to his scholar number.
