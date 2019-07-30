#include <stdio.h>
#include <unistd.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/wait.h>
#include<stdlib.h>

int main(int argc, char *argv[])
{
	pid_t pid;
	
	for(int i = 0; i<10; i++)
	{	
		pid = fork();

		if (pid < 0 ) {
			perror("fork error\n");
			exit(0);
		}
		else if(pid == 0) {// child
			printf("child process with pid : %d\n", getpid());
			exit(0);
		}

	}

	return 0;
}

