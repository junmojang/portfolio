#include<stdio.h>
#include <arpa/inet.h>
#include<string.h>
#include<stdlib.h>

int M[0x100000] = {0,};
int Reg[32] = {0,};
int pc = 0x0;
int fin = 0;
int nr = 0, ni = 0, nj =0 , nm = 0, nt = 0, nnt = 0, njump = 0, nhit = 0, nconflictm = 0, ncoldm = 0;;
float np = 0.0, nnp = 0.0;
int pv = 0;
int way = 0;
int oldest[256] = {0};

void fetch();
void decode();
void execute();
void control(int opcode);
void memory();
void writeback();

int SignExt(int imm);
int ReadMem(int addr);
int WriteMem(int addr, int value);
int CacheWriteback(int addr);

typedef struct
{
	int pc;
	int inst;
}IFID;

typedef struct
{
	int pc;
	int inst;
	int opcode, rs, rd, rt, shamt, funct, address, imm;
	int v1, v2;
	int simm;
	int Wreg;
	char format;
	
	int ALUSrc;
	int RegDst;
	int Branch;
	int MemRead;
	int MemWrite;
	int MemtoReg;
	int RegWrite;
}IDEX;

typedef struct
{
	int pc;
	int opcode;
	int v1, v2, v3;
	int rt;
	int simm;
	int Wreg;
	int funct;
	char format;

	int Branch;
	int MemRead;
	int MemWrite;
	int MemtoReg;
	int RegWrite;
}EXMM;

typedef struct
{
	int pc;
	int Rdata;
	int v3;
	int Wreg;
	char format;

	int MreadD;
	int RwriteD;
	int Wdata;
	int funct;
	int opcode;


	int MemtoReg;
	int RegWrite;
}MMWB;

IFID ifid_latch[2];
IDEX idex_latch[2];
EXMM exmm_latch[2];
MMWB mmwb_latch[2];

void setzero()
{
	memset(&ifid_latch[0], 0, sizeof(IFID));
	memset(&ifid_latch[1], 0, sizeof(IFID));
	memset(&idex_latch[0], 0, sizeof(IDEX));
	memset(&idex_latch[1], 0, sizeof(IDEX));
	memset(&exmm_latch[0], 0, sizeof(EXMM));
	memset(&exmm_latch[1], 0, sizeof(EXMM));
	memset(&mmwb_latch[0], 0, sizeof(MMWB));
	memset(&mmwb_latch[1], 0, sizeof(MMWB));
}

struct cacheline
{
	unsigned int tag : 18;
	unsigned int sca : 1;
	unsigned int valid : 1;
	unsigned int dirty : 1;
	int data[16];
};

struct cacheline Cache[4][256] = {0};
	
void main(int argc, char *argv[])
{
	int cycle = 1; // FILE ptr
        FILE *fp = NULL; //path string
        char *path; //read- in value
        int val = 0, i = 0;
        int res, inst;

	path = (char*) malloc(8*sizeof(char));
	printf("Put 0 for only result,  1 for all processing prints : ");
	scanf("%d",&pv);
	printf("Write the file name : ");
	scanf("%s", path);

	Reg[29] = 0x400000; //SP
	Reg[31] = -1; //RA

        if (argc == 2) {
                path = argv[1];
        } //open the file path
        fp = fopen(path, "rb");
        
	if(fp == NULL) {
                printf("invalid input file : %s\n", path);
                return;
        } //read file in from path
        
	while(1) {
           res = fread(&val, sizeof(val), 1, fp);
	   inst = ntohl(val);	   
	   M[i] = inst;
	   i++;

	   if (res == 0)
		break;
        }
	
	setzero();

	while(fin != -1){	   
	   fetch();
	   if(pv == 1)
	   printf("f: pc : 0x%X, inst : 0x%X, R[2] : %d, R[3] : %d\n",ifid_latch[0].pc,  ifid_latch[0].inst, Reg[2], Reg[3]);    
	   writeback();
	   if(pv == 1)
	   printf("w: R[2] : %d, R[3] : %d\n", Reg[2], Reg[3]);
	   decode();
	   if(pv == 1)
	   printf("d: pc : 0x%X, inst : 0x%X, R[2] : %d, R[3] : %d\n",idex_latch[0].pc, idex_latch[0].inst, Reg[2], Reg[3]);
	   execute();
	   if(pv == 1)
	   printf("e: pc : 0x%X, R[2] : %d, R[3] : %d\n",exmm_latch[0].pc, Reg[2], Reg[3]);
	   memory();
	   if(pv == 1)
	   printf("m: pc : 0x%X, R[2] : %d, R[3] : %d\n",mmwb_latch[0].pc, Reg[2], Reg[3]);
	   
	   ifid_latch[1] = ifid_latch[0];
	   idex_latch[1] = idex_latch[0];
	   exmm_latch[1] = exmm_latch[0];
	   mmwb_latch[1] = mmwb_latch[0]; 
	   if(pv == 1)
	   printf("-------------[cycle : %d]---------------------\n", cycle);
	   cycle++;
	}
	float percentb = (np / (np + nnp))*100;
	float a = nhit, b = nconflictm, c = ncoldm;
	float percentc = (a / (a + b + c))*100;

	printf("\nR[2] : %d, Cycle : %d\n", Reg[2],cycle-1);
	printf("# of Rtypt : %d\n",nr);
	printf("# of Itypt : %d\n",ni);
	printf("# of Jtypt : %d\n",nj);
	printf("# of Jump : %d\n",njump);
	printf("# of MAccess : %d\n",nhit + nconflictm+ ncoldm);
	printf("# of Branch Taken : %d\n", nt);
	printf("# of Branch Not Taken : %d\n",nnt);
	printf("Percentage of Branch Prediction correct: %f\n", percentb);
	printf("# of Hit : %d\n", nhit);
	printf("# of Conflict Miss : %d\n", nconflictm);	
	printf("# of Cold Miss : %d\n", ncoldm);	
	printf("Percentage of Hit Rate : %f\n",percentc);
	printf("Completes!\n\n");

        fclose(fp);
}

void fetch()
{
	int inst;
	inst = ReadMem(pc);
	ifid_latch[0].inst = inst;
	ifid_latch[0].pc = pc;
	pc = pc + 4;
	nm++;
}

void decode()
{	
	int inst = ifid_latch[1].inst;
	int pcd = ifid_latch[1].pc;
	int opcode = 0, rs = 0, rt = 0, rd = 0, shamt = 0, funct = 0, address = 0, imm = 0;
	char format;
	int v1 = 0, v2 = 0;

	opcode = (inst & 0xFC000000) >> 26;
	if(opcode == 0){
		if(pv == 1)
		printf("R type Format\n");
		format = 'R';
		rs = (inst & 0x3E00000) >> 21;
		rt = (inst & 0x1F0000) >> 16;
		rd = (inst & 0xF800) >> 11;
		shamt = (inst & 0x7C0) >> 6;
		funct = (inst & 0x3F);
        nr ++;
	}
	else if(opcode == 2 || opcode == 3){
		if(pv == 1)
		printf("J type Format\n");
		format = 'J';
		address = (inst & 0x3FFFFFF);
	nj++;
	}

	else{
		if(pv == 1)
		printf("I type Format\n");
		format = 'I';	
		rs = (inst & 0x3E00000) >> 21;
		rt = (inst & 0x1F0000) >> 16;
		imm = (inst & 0x0000FFFF);
	ni++;
	}

	int JumpAddr = pc & 0xF0000000 | address << 2;
	
	if(format == 'J'){
		switch(opcode){
                case 0x2:
                        if(pv == 1)
			printf("j : PC = %X\n", JumpAddr);
                        pc = JumpAddr;
			njump++;
                        break;
                case 0x3:
                        Reg[31] = pcd + 8;
                        pc = JumpAddr;
                        if(pv == 1)
			printf("jal : R[31] + PC +8, PC = %X",JumpAddr);
                        break;
		default :
			break;
		}
	}

	v1 = Reg[rs];
        v2 = Reg[rt];
	idex_latch[0].pc = pcd;
	idex_latch[0].opcode = opcode;
        idex_latch[0].rs = rs;
	idex_latch[0].rt = rt;
        idex_latch[0].rd = rd;
	idex_latch[0].v1 = v1;
        idex_latch[0].v2 = v2;

        idex_latch[0].shamt = shamt;
        idex_latch[0].funct = funct;
	idex_latch[0].inst = inst;
        idex_latch[0].simm = SignExt(imm);
        idex_latch[0].format = format;
	idex_latch[0].imm = imm;

	control(opcode);
}

void execute()
{	
	int ALUresult, ALUinput2;
	int pce = idex_latch[1].pc;
	int inst = idex_latch[1].inst;
	int ALUinput1 = idex_latch[1].v1;
	int opcode = idex_latch[1].opcode;
	int rs = idex_latch[1].rs;
	int rt = idex_latch[1].rt;
	int rd = idex_latch[1].rd;
	int funct = idex_latch[1].funct;
	int shamt = idex_latch[1].shamt;
	char format = idex_latch[1].format;
	int SignExtImm = idex_latch[1].simm;
	int BranchAddr = idex_latch[1].simm << 2;
	int ZeroExtImm = idex_latch[1].imm;

	int ALUSrc = idex_latch[1].ALUSrc;
	int RegDst = idex_latch[1].RegDst;
	int MemRead = idex_latch[1].MemRead;
	int MemWrite = idex_latch[1].MemWrite;


	int MemtoReg = idex_latch[1].MemtoReg;
	int RegWrite = idex_latch[1].RegWrite;

	int taluinput1=idex_latch[1].v1;
	int taluinput2=idex_latch[1].v2;	



	if((rs != 0) && (rs == mmwb_latch[1].Wreg) && mmwb_latch[1].RegWrite) 
		{
		if(mmwb_latch[1].opcode == 0x23)
			{
			if(pv == 1)
			printf("data dependency : about rs dist2(lw)\n");
			taluinput1 = mmwb_latch[1].Rdata;
			}
		else
			{
			if(pv == 1)
			printf("data dependency : about rs dist2(else)\n");
			taluinput1 = mmwb_latch[1].v3;
			}
		}
	if((rs != 0) && (rs == exmm_latch[1].Wreg) && exmm_latch[1].RegWrite)
		{
		if(pv == 1)
		printf("data dependency : about rs dist1\n");

		taluinput1 = exmm_latch[1].v3;
		}
	

	if((rt != 0) && (rt == mmwb_latch[1].Wreg) && mmwb_latch[1].RegWrite)
		{
		if(mmwb_latch[1].opcode == 0x23)
			{
			if(pv == 1)
			printf("data dependency : about rt dist2(lw)\n");
			taluinput2 = mmwb_latch[1].Rdata;
			}
		else
			{
			if(pv == 1)
			printf("data dependency : about rt dist2(else)\n");
			taluinput2 = mmwb_latch[1].v3;
			}
		}
	if((rt != 0) && (rt == exmm_latch[1].Wreg) && exmm_latch[1].RegWrite)
		{
		if(pv == 1)
		printf("data dependency : about rt dist1\n");
		taluinput2 = exmm_latch[1].v3;
		}
	

		ALUinput1 = taluinput1;
	if(ALUSrc == 0)
		ALUinput2 = taluinput2;
	else
		ALUinput2 = idex_latch[1].simm;


	if(format == 'R'){

		switch(funct){
		case 0x20:
			ALUresult = ALUinput1 + ALUinput2;
                        if(pv == 1)
			printf("add : R[%d] = R[%d] + R[%d]\n", rd, rs, rt);
			break;
		case 0x21:
			ALUresult = ALUinput1 + ALUinput2;
			if(pv == 1)
			printf("addu : R[%d] = R[%d] + R[%d]\n", rd,rs,rt);
			break;
		case 0x24:
			ALUresult = ALUinput1 & ALUinput2;
			if(pv == 1)
			printf("and : R[%d] = R[%d] & R[%d]\n",rd,rs,rt);
			break;
		case 0x8:
			pc = ALUinput1;
			pce = ALUinput1;
			if(pv == 1)
			printf("jr : PC = R[%d]\n",rs);
			break;
		case 0x27:
			ALUresult = ~(ALUinput1 | ALUinput2);
			if(pv == 1)
			printf("nor : R[%d] =~(R[%d] | R[%d])\n",rd,rs,rt);
			break;
		case 0x25:
			ALUresult = ALUinput1 | ALUinput2;
			if(pv == 1)
			printf("or : R[%d] = R[%d] | R[%d]\n",rd,rs,rt);
			break;
		case 0x2A:
			ALUresult = (ALUinput1 < ALUinput2) ? 1 : 0;
			if(pv == 1)
			printf("slt : R[%d] = (R[%d] < R[%d]) ? 1 : 0\n",rd,rs,rt);
			break;
		case 0x2B:
                        ALUresult = (ALUinput1 < ALUinput2) ? 1 : 0;
                        if(pv == 1)
			printf("sltu : R[%d] = (R[%d] < R[%d]) ? 1 : 0\n",rd,rs,rt);
			break;
		case 0x0:
			ALUresult = ALUinput2 << shamt;
			if(pv == 1)
			printf("sll : R[%d] = R[%d] << %d\n",rd,rs,shamt);
			break;
		case 0x2:
			ALUresult = ALUinput2 >> shamt;
			if(pv == 1)
			printf("srl : R[%d] + R[%d] >> %d\n",rd,rs,shamt);
			break;
		case 0x22:
			ALUresult = ALUinput1 - ALUinput2;
			if(pv == 1)
			printf("sub : R[%d] = R[%d] - R[%d]\n",rd,rs,rt);
			break;
		case 0x23:
			ALUresult = ALUinput1 - ALUinput2;
			if(pv == 1)
			printf("sub : R[%d] = R[%d] - R[%d]\n",rd,rs,rt);	
			break;
		default :
			break;
		}
	}
	else if(format == 'I'){
		switch(opcode){
		case 0x04://beq
			if(ALUinput1 == ALUinput2)
				{
				memset(&ifid_latch[0], 0, sizeof(IFID));
				memset(&idex_latch[0], 0, sizeof(IDEX));
				pc = pce + BranchAddr +4;
				nnt++;
				np++;
				}
			else
				{
				nt++;
				nnp++;
				}
			break;
		case 0x05://bne
			if(ALUinput1 != ALUinput2)
				{
				memset(&ifid_latch[0], 0, sizeof(IFID));
				memset(&idex_latch[0], 0, sizeof(IDEX));
				pc = pce + BranchAddr +4;
				nnt++;
				np++;
				}
			else
				{
				nt++;
				nnp++;
				}
			break;
		case 0x8:
			ALUresult = ALUinput1 + ALUinput2;
			if(pv == 1)
			printf("addi : R[%d] = R[%d] + %d\n",rt,rs,SignExtImm);
			break;
		case 0x9:
			ALUresult = ALUinput1 + ALUinput2;
			if(pv == 1)
			printf("addi : R[%d] = R[%d] + %d\n",rt,rs,SignExtImm);
			break;
		case 0xC:
			ALUresult = ALUinput1 & ZeroExtImm;
			if(pv == 1)
			printf("andi : R[%d] = R[%d] & %d\n",rt,rs,ZeroExtImm);
			break;
		case 0x24:
			ALUresult = M[ALUinput1 + SignExtImm] & 0xFF;
			if(pv == 1)
			printf("lbu : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);	
			break;
		case 0x25:
			ALUresult = M[ALUinput1+ SignExtImm] & 0xFFFF;
			if(pv == 1)
			printf("lhu : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);
			break;
		case 0x30:
			ALUresult = ALUinput1 + SignExtImm;
			if(pv == 1)
			printf("ll : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);
			break;
		case 0xD:
			ALUresult = ALUinput1 | ZeroExtImm;
			if(pv == 1)
			printf("ori : R[%d] = R[%d] | %d\n",rt,rs,ZeroExtImm);
			break;
		case 0xA:
			ALUresult = (ALUinput1 < SignExtImm)? 1: 0;
			if(pv == 1)
			printf("slti : R[%d] = (R[%d] < %d)? 1 : 0\n",rt,rs,SignExtImm);
			break;
		case 0xB:
			ALUresult = (ALUinput1 < SignExtImm)? 1: 0;
			if(pv == 1)
			printf("sltiu : R[%d] = (R[%d] < %d)? 1 : 0\n",rt,rs,SignExtImm);
			break;
		case 0x2B:
			if(pv == 1)
			printf("sw :\n");
			break;
		case 0x23:
			if(pv == 1)
			printf("lw :\n");
			break;
		case 0xF:
			ALUresult = idex_latch[1].imm << 16;
			break;
		default :
			break;
		}
	}
	
	exmm_latch[0].opcode = opcode;
	exmm_latch[0].v1 = ALUinput1;
	exmm_latch[0].rt = rt;
	exmm_latch[0].v3 = ALUresult;
	exmm_latch[0].v2 = taluinput2;
	exmm_latch[0].simm = SignExtImm;
	
	if(idex_latch[1].RegDst == 1)
		exmm_latch[0].Wreg = rd;
	else
		exmm_latch[0].Wreg = rt;
	
	exmm_latch[0].MemRead = idex_latch[1].MemRead;
	exmm_latch[0].MemWrite = idex_latch[1].MemWrite;
	exmm_latch[0].MemtoReg = idex_latch[1].MemtoReg;
	exmm_latch[0].RegWrite = idex_latch[1].RegWrite;
	exmm_latch[0].pc = pce;
}

void control(int opcode)
{
	int RegDst, ALUSrc, MemtoReg, RegWrite, MemRead, MemWrite; 

	if(opcode == 0)
		RegDst = 1;
	else
		RegDst = 0;
	if((opcode != 0) && (opcode != 0x4) && (opcode != 0x5))
		ALUSrc = 1;
	else
		ALUSrc = 0;
	if(opcode == 0x23)
		MemtoReg = 1;
	else
		MemtoReg = 0;
	if((opcode != 0x2B) && (opcode != 0x4) && (opcode != 0x5) && (opcode != 0x2) && (opcode != 0x3))
		RegWrite = 1;
	else
		RegWrite = 0;
	if(opcode == 0x23)
		{MemRead = 1;
		nm++;}
	else
		MemRead = 0;
	if(opcode == 0x2B)
		{MemWrite = 1;
		nm++;}
	else
		MemWrite = 0;

	idex_latch[0].ALUSrc = ALUSrc;
        idex_latch[0].RegDst = RegDst;
        idex_latch[0].MemRead = MemRead;
	idex_latch[0].MemWrite = MemWrite;
	idex_latch[0].MemtoReg = MemtoReg;
	idex_latch[0].RegWrite = RegWrite;
}

void memory()
{
	int Rdata;
	int pcm = exmm_latch[1].pc;
	int funct = exmm_latch[1].funct;
	int opcode = exmm_latch[1].opcode;
	int ALUinput1 = exmm_latch[1].v1;
	int SignExtImm = exmm_latch[1].simm;
	int rt = exmm_latch[1].rt;
	int ALUresult = exmm_latch[1].v3;
	int inputr = exmm_latch[1].v2;
	
	int MemRead = exmm_latch[1].MemRead;
	int MemWrite = exmm_latch[1].MemWrite;
	int MemtoReg = exmm_latch[1].MemtoReg;
	int RegWrite = exmm_latch[1].RegWrite;

	switch(opcode){
	case 0x28:
		M[ALUinput1 + SignExtImm] = inputr & 0xFF;
		break;
	case 0x29:
		M[ALUinput1 + SignExtImm] = inputr & 0xFFFF;
		break;
	case 0x2B:
		WriteMem(ALUinput1 + SignExtImm, inputr);
		break;
	case 0x23:
                Rdata = ReadMem(ALUinput1 + SignExtImm);
		break;
	default :
		break;
	}
	
	mmwb_latch[0].pc = pcm;
	mmwb_latch[0].funct = funct;
	mmwb_latch[0].opcode = opcode;
	mmwb_latch[0].v3 = ALUresult;
	mmwb_latch[0].Wreg = exmm_latch[1].Wreg;
	mmwb_latch[0].Rdata = Rdata;
	mmwb_latch[0].MemtoReg = exmm_latch[1].MemtoReg;	
	mmwb_latch[0].RegWrite = exmm_latch[1].RegWrite;
	mmwb_latch[0].format = exmm_latch[1].format;
}

void writeback()
{
	int Wreg = mmwb_latch[1].Wreg;
	int rd, rt;
	int pcw = mmwb_latch[1].pc;

	int opcode = mmwb_latch[1].opcode;
	int funct = mmwb_latch[1].funct;
	char format = mmwb_latch[1].format;
	int MemtoReg = mmwb_latch[1].MemtoReg;
	int ALUresult = mmwb_latch[1].v3;
	int Rdata = mmwb_latch[1].Rdata;
	int RegWrite = mmwb_latch[1].RegWrite;
	int inputr;

	if(RegWrite == 0)
		return;

	else
	{
	if(MemtoReg == 0)
		inputr = ALUresult;
	else
		inputr = Rdata;
	
	
	if((format == 'R')&&(funct != 0x8))
		Reg[Wreg] =inputr;
	else if((RegWrite == 1)&&(opcode!=0x3)&&(opcode!=0x2))
		Reg[Wreg] = inputr;
	else
		return;
	}
	
	fin = mmwb_latch[1].pc;
	
}

int SignExt(int imm)
{
	if (imm >= 0x8000)
		return 0xffff0000 | imm;
	else
		return imm;
}

int ReadMem(int addr)
{
	int tag;
	int index;
	int offset;
	int Dest;
	
	tag = (addr & 0xFFFFC000) >> 14;
	index = (addr & 0x3FC0) >> 6;
	offset = (addr & 0x3F);
	Dest = addr & 0xFFFFFFC0;
		
	for(int j = 0; j < 4; j++)
	{
		if(oldest[index] >3)
		oldest[index] = 0;

		way = oldest[index];

		if((Cache[j][index].tag == tag) && Cache[j][index].valid == 1)
		{
			if(pv == 1)
			printf("@@@@@@@@@@@@@@HIT@@@@@@@@@@@\n");
			Cache[j][index].sca = 1;
			nhit++;
			return Cache[j][index].data[offset/4];
		}
	
	else
	{	
		if((Cache[way][index].sca == 0) && (Cache[way][index].dirty == 1))
		{	
			int tempaddr;
			tempaddr = Cache[way][index].tag << 14 | index << 6;
			CacheWriteback(tempaddr);
			Cache[way][index].tag = tag;
			Cache[way][index].valid = 1;
			for(int i = 0; i < 16; i++)
			{
				Cache[way][index].data[i] = M[Dest/4 + i];
			}
			Cache[way][index].dirty = 0;
			nconflictm++;

			return Cache[way][index].data[offset/4];

		}
		else if(Cache[way][index].sca == 1)
		{
			Cache[way][index].sca = 0;
			oldest[index]++;
		}
		else
		{
			Cache[way][index].tag = tag;
			Cache[way][index].valid = 1;
			for(int i = 0; i < 16; i++)
			{
				Cache[way][index].data[i] = M[Dest/4 + i];
			}
			oldest[index]++;
			ncoldm++;

			return Cache[way][index].data[offset/4];
		}	
	}
	}
}

int WriteMem(int addr, int value)
{
	int tag;
	int index;
	int offset;
	int Dest;

	tag = (addr & 0xFFFFC000) >> 14;
	index = (addr & 0x3FC0) >> 6;
	offset = (addr & 0x3F);
	Dest = addr & 0xFFFFFFC0;

	for(int j = 0; j < 4; j++)
	{
	if(oldest[index] >3)
		oldest[index] = 0;

	way = oldest[index];

	if(Cache[j][index].tag == tag)
	{
		Cache[j][index].data[offset/4] = value;
		Cache[j][index].dirty = 1;
		nhit++;
	}
	else
	{
		if((Cache[way][index].sca == 0) && (Cache[way][index].dirty == 1))
		{	
			int tempaddr;
			tempaddr = Cache[way][index].tag << 14 | index << 6;
			CacheWriteback(tempaddr);
			Cache[way][index].tag = tag;
			Cache[way][index].valid = 1;
			for(int i = 0; i < 16; i++)
			{
				Cache[way][index].data[i] = M[Dest/4 + i];
			}
			Cache[way][index].data[offset/4] = value;
			nconflictm++;
		}
		else if(Cache[way][index].sca == 1)
		{
			Cache[way][index].sca = 0;
			oldest[index]++;
		}
		else
		{
			Cache[way][index].tag = tag;
			Cache[way][index].valid = 1;
			for(int i = 0; i < 16; i++)
			{
				Cache[way][index].data[i] = M[Dest/4 + i];
			}
			Cache[way][index].data[offset/4] = value;
			Cache[way][index].dirty = 1;
			ncoldm++;
		}
	}
	}
}

int CacheWriteback(int addr)
{
	int tag;
	int index;
	int offset;
	int Dest;

	tag = (addr & 0xFFFFC000) >> 14;
	index = (addr & 0x3FC0) >> 6;
	offset = (addr & 0x3F);
	Dest = addr & 0xFFFFFFC0;
		
		if(pv == 1)
		printf("***************dirty = 1**********\n");
		for(int i = 0; i < 16; i++)
		{
			M[Dest/4 + i] = Cache[way][index].data[i];
		}

}
