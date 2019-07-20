#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>
#include<string.h>
#include<stdbool.h>
#define SEP1 " "

bool isoperator(char *opt)
{
        if(*opt == '+' || *opt == '-' || *opt == '*' || *opt == '/')
                return true;
        else
                return false;
}
long change(char* ptr);
long calc(char* operator, long op1, long op2);
int sepR(char* operand);
long Reg[10]={0};

int main()
{
    char *token, *temp[2];
    char* operator;
    char* tmpop1;
    char* tmpop2;
    long operand1, operand2;
    char** saveptr = NULL;
    saveptr = (char**)malloc(sizeof(char*)*60);
        for(int k = 0; k<60; k++)
            *(saveptr + k) = (char*)malloc(sizeof(char)*3);

    int Reg1, Reg2;
    int i = 0;
    FILE *stream;
    char *line = NULL;
    size_t len = 0;
    ssize_t read;

    stream = fopen("input02.txt", "r");
    if (stream == NULL)
        exit(EXIT_FAILURE);

    while((read = getline(&line, &len, stream)) != -1)
    {
        printf("%s", line);
        token = strtok_r(line, SEP1, &temp[0]);
        strcpy(saveptr[i], token);
        operator = saveptr[i];

        token  = strtok_r(saveptr[i],SEP1,&temp[1]);
        token = strtok_r(NULL, SEP1, &temp[0]);
        i++;
        strcpy(saveptr[i],token);
        tmpop1 = saveptr[i];

        token  = strtok_r(saveptr[i],SEP1,&temp[1]);
        token = strtok_r(NULL, SEP1, &temp[0]);
        i++;
        strcpy(saveptr[i], token);
        tmpop2 = saveptr[i];

        if(isoperator(operator))
        {
		if(!strncmp(tmpop1,"R",1))//tmpop1이 레지스터라면
		{
			if(!strncmp(tmpop2,"R",1))//tmpop2도 레지스터라면
			{
				Reg1 = sepR(tmpop1);
				Reg2 = sepR(tmpop2);
				operand1 = Reg[Reg1];
				operand2 = Reg[Reg2];
				Reg[0] = calc(operator, operand1, operand2);
                                printf("Reg[0] is %ld <- %ld%s%ld\n\n", Reg[0], operand1, operator, operand2);
	
			}
			else//tmpo2는 숫자라면
			{
				Reg1 = sepR(tmpop1);
				operand1 = Reg[Reg1];
				operand2 = change(tmpop2);
				Reg[0] = calc(operator, operand1, operand2);
                                printf("Reg[0] is %ld <- %ld%s%ld\n\n", Reg[0], operand1, operator, operand2);

			}
		}
		else//tmpop1이 숫자라면
		{
			if(!strncmp(tmpop2,"R",1))//tmpop2가 레지스터라면
			{
				Reg2 = sepR(tmpop2);
				operand1 = change(tmpop1);
				operand2 = Reg[Reg2];
				Reg[0] = calc(operator, operand1, operand2);
                                printf("Reg[0] is %ld <- %ld%s%ld\n\n", Reg[0], operand1, operator, operand2);
			}
			else//tmpop2도 숫자라면
			{			
				operand1 = change(tmpop1);
                		operand2 = change(tmpop2);
                		Reg[0] = calc(operator, operand1, operand2);
				printf("Reg[0] is %ld <- %ld%s%ld\n\n", Reg[0], operand1, operator, operand2);
			}
		}
	}
        else
        {
                if(!strcmp(operator,"M"))
                {       
			Reg1 = sepR(tmpop1);
                        if(!strncmp(tmpop2,"R",1))
			{
				Reg2 = sepR(tmpop2);
				Reg[Reg1] = Reg[Reg2];
	                        printf("Reg[%d] becomes %ld\n\n", Reg1, Reg[Reg2]);
			}
			else
			{
				operand2 = change(tmpop2);
				Reg[Reg1] = operand2;
				printf("Reg[%d] becomes %ld\n\n", Reg1, operand2);
			}	
                }
                else if(!strcmp(operator,"H"))
                {       printf("system is halted\n\n\n");
                        return 0;
                }
                else
                        printf("Error\n");
        }
        i++;
    }
    free(line);
    fclose(stream);
    exit(EXIT_SUCCESS);
}

long change(char* ptr)
{
        long tmp1;
        tmp1 = strtol(ptr,NULL,16);
    
	return tmp1;
}

long calc(char* operator, long op1, long op2)
{
	long temp;
        switch(*operator)
       {
	case '+' :
	temp = op1 + op2;
        return temp;
	break;

        case '-' :
	temp = op1 - op2;
 	return temp;      
	break;

        case '*' :
	temp = op1 * op2;
        return temp;
	break;

        case '/' :
        if(op2 == 0)
                {
		printf("The Denominator cannot be 0\n\n");
		return Reg[0];
		break;
		}
        else
		{
		temp = op1 / op2;
                return temp;
		break;
		}
        default :
        printf("Operator Error\n\n");
        break;
        }
}

int sepR(char* tmpop)
{
        int tmp1;
        char* tmp2;
        tmp2 = tmpop+1;
        tmp1 = atoi(tmp2);

        return tmp1;
}                                                                                                            
