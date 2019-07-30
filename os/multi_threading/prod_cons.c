#include <pthread.h>
#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include<time.h>
#define ASCII_SIZE 256

void char_stat(char *line);
int stat2[ASCII_SIZE];
int p = 0, gc= 0;
int read_flag = 0;
size_t length = 0;

typedef struct sharedobject {
	FILE *rfile;
	int linenum;
	char *line[100];
	pthread_mutex_t lock[100];
	pthread_mutex_t lock2;
	pthread_mutex_t lock3;
	int full[100];
	int done[100];
} so_t;

void *producer(void *arg){

	so_t *so = arg;
	int *ret = malloc(sizeof(int));
	FILE *rfile = so->rfile;
	int i = 0;
	char *line = NULL;
	size_t len = 0;
	ssize_t read = 0;

	while(1){
		read = getdelim(&line, &len, '\n', rfile);
		pthread_mutex_lock(&so->lock[p]);
		while(so->full[p] == 1) {
			int temp = p;
			p++; p = p % 100;
			pthread_mutex_unlock(&so->lock[temp]);
			pthread_mutex_lock(&so->lock[p]);
		}
		if (read == -1) {
			for(int k = 0; k<100; k++){
			if(so->full[k] == 0){
			so->full[k] = 1;
			so->line[k] = NULL;
			}
			}
			so->full[p] = 1;
			so->line[p] = NULL;
			read_flag = 1;
			pthread_mutex_unlock(&so->lock[p]);
			break;
		}

		so->linenum = i;
		so->line[p] = strdup(line); 
		i++;
		so->full[p] = 1;
		int temp = p;
		p++; p = p % 100;
		pthread_mutex_unlock(&so->lock[temp]);
	}
	free(line);
	*ret = i;
	pthread_exit(ret);
}

void *consumer(void *arg) {
	so_t *so = arg;
	int *ret = malloc(sizeof(int));
	int i = 0;
	int c = 0;
	int len, temp;
	char *line;
	int end = 0;

	while (1) {
		pthread_mutex_lock(&so->lock2);
		c = gc;
		gc++; gc= gc%100;
		pthread_mutex_unlock(&so->lock2);

		pthread_mutex_lock(&so->lock[c]);
		if(read_flag == 0){
			while(so->full[c] == 0){
				temp = c;
				c++; c = c % 100;
				pthread_mutex_unlock(&so->lock[temp]);
				pthread_mutex_lock(&so->lock[c]); 
				if(read_flag == 1) break;
			}
		}

			if(so->line[c] == NULL){
				pthread_mutex_unlock(&so->lock[c]);
				break;
			}
			if(so->full[c] == 0){
				pthread_mutex_unlock(&so->lock[c]);
				continue;	
			}
				line = so->line[c];
				pthread_mutex_lock(&so->lock3);
				char_stat(line);
				pthread_mutex_unlock(&so->lock3);
				so->full[c] = 0;
				so->line[c] = NULL;
				i++;
				//free(line);
				pthread_mutex_unlock(&so->lock[c]);
		}
	free(line);
	*ret = i;
	pthread_exit(ret);
}
int main (int argc, char *argv[])
{
	pthread_t prod[100];
	pthread_t cons[100];
	int Nprod, Ncons;
	int rc;   long t;
	int *ret;
	int i;
	FILE *rfile;
	int total = 0;

	if (argc == 1) {
		exit (0);
	}
	so_t *share = malloc(sizeof(so_t));
	memset(share, 0, sizeof(so_t));
	memset(stat2, 0, sizeof(stat2));

	rfile = fopen((char *) argv[1], "r");
	if (rfile == NULL) {
		perror("rfile");
		exit(0);
	}
	if (argv[2] != NULL) {
		Nprod = atoi(argv[2]);
		if (Nprod > 100) Nprod = 100;
		if (Nprod == 0) Nprod = 1;
	} else Nprod = 1;
	if (argv[3] != NULL) {
		Ncons = atoi(argv[3]);
		if (Ncons > 100) Ncons = 100;
		if (Ncons == 0) Ncons = 1;
	} else Ncons = 1;

	share->rfile = rfile;
	for(int k = 0; k < 100; k++){
		pthread_mutex_init(&share->lock[k], NULL);
	}
	for (i = 0 ; i < Nprod ; i++)
		pthread_create(&prod[i], NULL, producer, share);
	for (i = 0 ; i < Ncons ; i++)
		pthread_create(&cons[i], NULL, consumer, share);

	for (i = 0 ; i < Ncons ; i++) {
		rc = pthread_join(cons[i], (void **) &ret);
	}
	for (i = 0 ; i < Nprod ; i++) {
		rc = pthread_join(prod[i], (void **) &ret);
	}
	printf("        A        B        C        D        E        F        G        H        I        J        K        L        M        N        O        P        Q        R        S        T        U        V        W        X        Y        Z\n");
	printf("%8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d %8d\n",
			stat2['A']+stat2['a'], stat2['B']+stat2['b'],  stat2['C']+stat2['c'],  stat2['D']+stat2['d'],  stat2['E']+stat2['e'],
			stat2['F']+stat2['f'], stat2['G']+stat2['g'],  stat2['H']+stat2['h'],  stat2['I']+stat2['i'],  stat2['J']+stat2['j'],
			stat2['K']+stat2['k'], stat2['L']+stat2['l'],  stat2['M']+stat2['m'],  stat2['N']+stat2['n'],  stat2['O']+stat2['o'],
			stat2['P']+stat2['p'], stat2['Q']+stat2['q'],  stat2['R']+stat2['r'],  stat2['S']+stat2['s'],  stat2['T']+stat2['t'],
			stat2['U']+stat2['u'], stat2['V']+stat2['v'],  stat2['W']+stat2['w'],  stat2['X']+stat2['x'],  stat2['Y']+stat2['y'],
			stat2['Z']+stat2['z']);

	pthread_exit(NULL);
	exit(0);
}

void char_stat(char *line)
{
	char *cptr = NULL;
	char *substr = NULL;
	char *brka = NULL;
	char *sep = "{ } ( ) [ ] , ; \" \n \t ^ ";

	cptr = strdup(line);
	for (substr = strtok_r(cptr, sep, &brka); substr; substr = strtok_r(NULL, sep, &brka))
	{
		length = strlen(substr);
		for (int i = 0 ; i < length ; i++){
			if(*substr < 256 && *substr > 1) {
				stat2[*substr]++;
			}
			substr++;
		}
	}
}
