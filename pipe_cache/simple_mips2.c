#include<stdio.h>
int Memory[0x8000];

void main(int argc, char *argv[])
{
        // FILE ptr
        FILE *fp = NULL;
        //path string
        char *path = "simple.bin";
        //read- in value
        int val = 0;
        int res;
        int inst;
        int i = 0;

        if (argc == 2) {
                path = argv[1];
        }
        //open the file path
        fp = fopen(path, "rb");
        if(fp == NULL) {
                printf("invalid input file : %s\n", path);
                return;
        }
        //int a loop
        //read file in from path
        while(1) {
           res = fread(&val, sizeof(val), 1, fp);
           inst = (val & 0xFF) <<24
           |(val & 0xFF00) <<8
           |(val & 0xFF0000) >>8
           |(val & 0xFF000000) >>24;

           printf("0x%08X --> 0x%08X \n", val, inst);
           Memory[i] = inst;
           i++;
           break;
        }
        //close the file
        fclose(fp);
}
