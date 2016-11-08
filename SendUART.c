#include "SendUART.h"
int nFd = 0;
struct termios stNew;
struct termios stOld;

int SerialInit()
{
    nFd = open(DEVICE, O_RDWR|O_NOCTTY|O_NDELAY);
    if(-1 == nFd)
    {
        perror("Open Serial Port Error!\n");
        return -1;
    }
    if( (fcntl(nFd, F_SETFL, 0)) < 0 )
    {
        perror("Fcntl F_SETFL Error!\n");
        return -1;
    }
    if(tcgetattr(nFd, &stOld) != 0)
    {
        perror("tcgetattr error!\n");
        return -1;
    }
    stNew = stOld;
    cfmakeraw(&stNew);//将终端设置为原始模式，该模式下所有的输入数据以字节为单位被处理
    //set BAUDRATE
    cfsetispeed(&stNew, BAUDRATE);//115200
    cfsetospeed(&stNew, BAUDRATE);
    //set databits
    stNew.c_cflag |= (CLOCAL|CREAD);
    stNew.c_cflag &= ~CSIZE;
    stNew.c_cflag |= CS8;
    //set parity
    stNew.c_cflag &= ~PARENB;
    stNew.c_iflag &= ~INPCK;
    //set stopbits
    stNew.c_cflag &= ~CSTOPB;
    stNew.c_cc[VTIME]=0;    //指定所要读取字符的最小数量
    stNew.c_cc[VMIN]=1; //指定读取第一个字符的等待时间，时间的单位为n*100ms
                //如果设置VTIME=0，则无字符输入时read（）操作无限期的阻塞
    tcflush(nFd,TCIFLUSH);  //清空终端未完成的输入/输出请求及数据。
    if( tcsetattr(nFd,TCSANOW,&stNew) != 0 )
    {
        perror("tcsetattr Error!\n");
        return -1;
    }
    return nFd;
}

//ascii转16
unsigned char ASCI_16(char a) 
{ 
  int b;
  if(a>=0x30&&a<=0x39)
    b=a-0x30;
  else if (a>=0x41&&a<=0x46) 
    b=a-0x41+10;
  else if (a>=0x61&&a<=0x66) 
    b=a-0x61+10; 
  return b;
}

//16转ascii
unsigned char HexToChar(unsigned char bChar)  
{  
    if((bChar>=0x30)&&(bChar<=0x39))  
    {  
        bChar -= 0x30;  
    }  
    else if((bChar>=0x41)&&(bChar<=0x46)) // Capital  
    {  
        bChar -= 0x37;  
    }  
    else if((bChar>=0x61)&&(bChar<=0x66)) //littlecase  
    {  
        bChar -= 0x57;  
    }  
    else   
    {  
        bChar = 0xff;  
    }  
    return bChar;  
}  


unsigned char implode(char a,char b)
{
	return a*16+b;
}
main(int argc,char **argv)
{
    //----- TX BYTES -----
    unsigned char tx_buffer[21];
	unsigned char tx[21];
    unsigned char *p_tx_buffer;
    int lx;

	if( SerialInit() == -1 )
    {
        perror("SerialInit Error!\n");
        return -1;
    }

    p_tx_buffer = &tx_buffer[0];
    strcpy(tx_buffer, argv[1]);
	//printf("para");
	//printf(tx_buffer);				//输出传入的参数
	//printf("nihaao\n");
	lx=sizeof(tx_buffer);
	
	int sendNum = 0;
	int SendNumber = 0;


	//HexToChar(tx_buffer[13]) == 0 && HexToChar(tx_buffer[14]) == 7
	if(HexToChar(tx_buffer[13]) == 0 && HexToChar(tx_buffer[14]) == 4
		&& HexToChar(tx_buffer[15]) == 0 && HexToChar(tx_buffer[16]) == 2)
	{
		//printf("nihaoa");
		serialPutchar(nFd,implode(ASCI_16('E'),ASCI_16('F')));
		serialPutchar(nFd,implode(ASCI_16('0'),ASCI_16('1')));
		int i = 1 , j=0;
		for(i;i<23 ;i+=2)
		{
			tx[i] = ASCI_16(tx_buffer[i]);
			tx[j] = ASCI_16(tx_buffer[i+1]);
			//printf("%x\n",(tx[i]));
			//printf("%x\n",(tx[j]));
			if (nFd != -1)
			{
				serialPutchar(nFd,implode(tx[i],tx[j]));
			}
			j++;
		}

		if(serialDataAvail(nFd) >=0)
		{
			int x = serialDataAvail(nFd);
			while(x>-12)
			{
				printf("%02X\n",serialGetchar(nFd));
				x--;
			}
		}
	}
	//自动注册单独处理
	else if(HexToChar(tx_buffer[15]) == 1 && HexToChar(tx_buffer[16]) == 0)
	{
		//printf("register");
		int i = 1 , j=0;
			//包头
		serialPutchar(nFd,implode(ASCI_16('E'),ASCI_16('F')));
		serialPutchar(nFd,implode(ASCI_16('0'),ASCI_16('1')));
		for(i;i<lx ;i+=2)
		{
			tx[i] = ASCI_16(tx_buffer[i]);
			tx[j] = ASCI_16(tx_buffer[i+1]);
			//printf("%c\n",tx[i]);
			//printf("%c\n",tx[j]);
			if (nFd != -1)
			{
				serialPutchar(nFd,implode(tx[i],tx[j]));
			}
			j++;
		}

		//printf("%d\n",serialDataAvail(nFd));
		//printf("%x\n",serialGetchar(nFd));
		if(serialDataAvail(nFd) >=0)
		{
			int x = serialDataAvail(nFd);
			//单个数据包为14
			while(x>-14)	
			{
				printf("%02X\n",serialGetchar(nFd));
				x--;
			}
		}
		
	}
	else if(HexToChar(tx_buffer[13]) == 0 && HexToChar(tx_buffer[14]) == 3
		&& HexToChar(tx_buffer[15]) == 0 && tx_buffer[16] == 'a')
	{
		//printf("image\n");
		serialPutchar(nFd,implode(ASCI_16('E'),ASCI_16('F')));
		serialPutchar(nFd,implode(ASCI_16('0'),ASCI_16('1')));
		int i = 1 , j=0;

		for(i;i<lx ;i+=2)
		{
			tx[i] = ASCI_16(tx_buffer[i]);
			tx[j] = ASCI_16(tx_buffer[i+1]);
			//printf("%x\n",tx[i]);
			//printf("%x\n",tx[j]);
			if (nFd != -1)
			{
				serialPutchar(nFd,implode(tx[i],tx[j]));
			}
			j++;
		}
		if(serialDataAvail(nFd) >=0)
		{
			float x = serialDataAvail(nFd);
			//单个数据包长度为12
			while(x>-40044)
			{
				printf("%02X",serialGetchar(nFd));
				x--;
			}
		}		
	}
	else 
	{
		//printf("unregister\n");
		int i = 1 , j=0;
			//包头
		serialPutchar(nFd,implode(ASCI_16('E'),ASCI_16('F')));
		serialPutchar(nFd,implode(ASCI_16('0'),ASCI_16('1')));
		for(i;i<lx ;i+=2)
		{
			tx[i] = ASCI_16(tx_buffer[i]);
			tx[j] = ASCI_16(tx_buffer[i+1]);
			//printf("%c\n",tx[i]);
			//printf("%c\n",tx[j]);
			if (nFd != -1)
			{
				serialPutchar(nFd,implode(tx[i],tx[j]));
			}
			j++;
		}

		if(serialDataAvail(nFd) >=0)
		{
			int x = serialDataAvail(nFd);
			//单个数据包长度为12
			while(x>-12)	
			{
				printf("%02X\n",serialGetchar(nFd));
				x--;
			}
		}
	}
	

	//printf("nihao");
		
    //----- CLOSE THE UART -----
    close(nFd);
}


