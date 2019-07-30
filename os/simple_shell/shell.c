#include <stdio.h>
#include <unistd.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/wait.h>
#include<sys/stat.h>
#include<stdlib.h>
#include<string.h>
#include<signal.h>
#include<time.h>
#define MAX_LINE 256

int main()
{
	int i = 0, j = 0;
	char *env, *str;
	char *tok[100], *saveptr;
	char *exec_cmd, *cmd_tok[100], *time_tok[100];
	exec_cmd = (char*)malloc(sizeof(char)*100);
	struct stat fstat_buf;
	int ret;

	char *cmd, *full;
	cmd = (char*)malloc(sizeof(char)*100);
	full = (char*)malloc(sizeof(char)*100);

	char *savepath, *saveuser, *savepwd;
	savepath = (char*)malloc(sizeof(char)*200);
	saveuser = (char*)malloc(sizeof(char)*200);
	savepwd = (char*)malloc(sizeof(char)*200);

	char *path = getenv("PATH");
	strcpy(savepath, path);
	char *user = getenv("USER");
	strcpy(saveuser, user);
	char *pwd = getenv("PWD");
	strcpy(savepwd, pwd);

	struct tm* now;
	time_t now_t;

	time(&now_t);
	now = localtime(&now_t);



	while(1){
		printf("Put the function you want execute : ");
	
		fgets(cmd, MAX_LINE, stdin);
		cmd[strlen(cmd)-1] = '\0';

		for(i = 0, str = cmd; ;str = NULL, i++){
			cmd_tok[i] = strtok_r(str, " ", &saveptr);
			if(cmd_tok[i] == NULL) break; 
		}

		if(strncmp(cmd,"quit",4)==0)
			break;
		else if(strncmp(cmd,"user",4)==0){
			printf("%s\n",user);
			continue;


		}
		else if(strncmp(pwd,"pwd",3)==0){
			printf("%s\n",pwd);
			continue;
		}
		else if(strncmp(cmd,"/",1)==0){
			full = cmd;
			
			ret = stat(full, &fstat_buf);
			if (ret == -1){
				//perror("stat");
			}//if
			else{
				strcpy(exec_cmd, full);
			}
		}
		else if(strncmp(cmd,"time",4)==0){
			for(i = 0, str = asctime(now); ;str = NULL, i++){
				time_tok[i] = strtok_r(str, " ", &saveptr);
				if(time_tok[i] == NULL) break; 
			}
			printf("%s\n",time_tok[3]);
			continue;
		}
		else{
			env = path;	
			for(j = 0,str=env; ;str = NULL,j++)
			{
				tok[j] = strtok_r(str, ":", &saveptr);
				if(tok[j] == NULL) 
					break;

				strcpy(full, tok[j]);
				full = strcat(full,"/");

				full = strcat(full, cmd_tok[0]);
				//printf("cmd_tok[1] : %s\n", cmd_tok[1]);
				//printf("full : %s\n",full);

				ret = stat(full, &fstat_buf);
				//printf("ret : %d\n", ret);
				if (ret == -1){
					//perror("stat");
				}//if
				else{
					strcpy(exec_cmd, full);
				}
				//printf("getenv(path) : %s\n", getenv("TIME"));
				//printf("---------------------------\n");
			}//for
		}//else
		strcpy(path, savepath);

		pid_t cpid;

		cpid = fork();
		if(cpid < 0) {
			perror("fork error\n");
			exit(0);
		}
		else if(cpid == 0){//child process
			//printf("exec_cmd : %s, cmd_tok[1] : %s\n",exec_cmd, cmd_tok[1]);
			execve(exec_cmd, cmd_tok, '\0');
			exit(0);
		}
		else{//parent process
			wait(0);
		}

	}//while

	return 0;
}
