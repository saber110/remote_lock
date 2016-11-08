#ifndef _COMM_H
#define _COMM_H
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <termios.h>
#include <unistd.h>   //Used for UART
#include <fcntl.h>    //Used for UART
#include <termios.h>  //Used for UART
#include <string.h>
#include <ctype.h>
#include <wiringSerial.h>
#define BAUDRATE B115200 ///Baud rate : 115200
#define DEVICE "/dev/ttyAMA0"
#define SIZE 1024
#define SENDSIZE 12
#endif