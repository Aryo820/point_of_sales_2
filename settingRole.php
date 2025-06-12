<?php
function group1()
{
    // 4: instructor
    return ['4'];
}
function group2()
{
    // 6: students
    return ['6'];
}
function group3()
{
    // 2: administrator, 3: admin, 5: PIC
    return ['2', '3', '5'];
}

function role_available()
{
    // 4: instructor, 6:students
    return ['4', '6'];
}

// in_array
function canAddModul($role)
{
    if (in_array($role, group1())) {
        return true;
    }
}
