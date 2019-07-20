#include<stdio.h>
#include <arpa/inet.h>
#include<string.h>

int M[0x800000] = {0,};
int SignExt(int imm);
int inst;
int opcode;
int rs, rt, rd, shamt, funct, imm, address;
int Reg[32] = {0,};
signed int PC = 0x0;
char format;
int RreadR1, RreadR2, RwriteR, RwriteD, RreadD1, RreadD2;
int ALUinput1, ALUinput2, ALUresult;
int RegDst, Jump, Branch, MemRead, MemtoReg, MemWrite, ALUSrc, RegWrite, PCSrc1, PCSrc2;
int Maddress, MwriteD, MreadD;
int nr = 0, ni = 0, nj =0 , nm = 0, nb = 0;

void fetch();
void decode();
void execute();
void control();
void LStoMem();
void writeback();
int JumpAddr;
int SignExtImm;
int BranchAddr;
int ZeroExtImm;

void main(int argc, char *argv[])
{
	int cycle = 1; // FILE ptr
        FILE *fp = NULL; //path string
        char *path = "input4.bin"; //read- in value
        int val = 0, i = 0;
        int res, inst;
	
	Reg[29] = 0x800000; //SP
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

	while((PC/4) <= i && PC != -1){
	   printf("\n---------------cycle : %d--------------------------------\n", cycle);

	   fetch();
	   decode();
	   execute();
	   LStoMem();
	   writeback();

	   funct = 0;
	   opcode = 0;
	printf("PC : 0x%08X, ALUresult : %X\n",PC, ALUresult);
	printf("R[2] : %X, R[3] : %X, R[4] : %X,R[30] : %X, R[31] : %X\n", Reg[2], Reg[3], Reg[4],Reg[30],  Reg[31]);
	cycle++;
	}
	printf("--------------------------------------------------------\n");
	printf("\nR[2] : %d, Cycle : %d\n", Reg[2],cycle-1);
	printf("# of Rtypt : %d\n",nr);
	printf("# of Itypt : %d\n",ni);
	printf("# of Jtypt : %d\n",nj);
	printf("# of MAccess : %d\n",nm);
	printf("# of Branch Taken : %d\n",nb);
	printf("Completes!\n\n");
        //close the file
        fclose(fp);
	

}

void fetch()
{
	inst = M[PC / 4];
	printf("instruction : %08X\n", inst);
        printf("\nPC : 0x%08X \n",PC);

}

void decode()
{
	opcode = (inst & 0xFC000000) >> 26;
	if(opcode == 0){
		printf("R type Format\n");
		format = 'R';
		rs = (inst & 0x3E00000) >> 21;
		rt = (inst & 0x1F0000) >> 16;
		rd = (inst & 0xF800) >> 11;
		shamt = (inst & 0x7C0) >> 6;
		funct = (inst & 0x3F);
        printf("funct : %X, R[rs] : %X, R[rt] : %X, R[rd] : %X\n", funct, Reg[rs], Reg[rt], Reg[rd]);
 	nr ++;
	}
	else if(opcode == 2 || opcode == 3){
		printf("J type Format\n");
		format = 'J';
		address = (inst & 0x3FFFFFF);
	nj++;
	}

	else{
		printf("I type Format\n");
		format = 'I';	
		rs = (inst & 0x3E00000) >> 21;
		rt = (inst & 0x1F0000) >> 16;
		imm = (inst & 0x0000FFFF);
	printf("opcode : %X R[%d] : %X, R[31] : %X SEI : %X\n", opcode,rs, Reg[rs], Reg[31], SignExt(imm));
	ni++;
	}
}

void execute()
{	

	int JumpAddr = PC & 0xF0000000 | address << 2;
	int SignExtImm = SignExt(imm);
	int BranchAddr = SignExtImm << 2;
	int ZeroExtImm = imm;
	control();

	if(format == 'R'){
		switch(funct){
		case 0x20:
			ALUresult = ALUinput1 + ALUinput2;
                        printf("add : R[%d] = R[%d] + R[%d]\n", rd, rs, rt);
			break;
		case 0x21:
			ALUresult = ALUinput1 + ALUinput2;
			printf("addu : R[%d] = R[%d] + R[%d]\n", rd,rs,rt);
			break;
		case 0x24:
			ALUresult = ALUinput1 & ALUinput2;
			printf("and : R[%d] = R[%d] & R[%d]\n",rd,rs,rt);
			break;
		case 0x8:
			PC = ALUinput1;
			printf("jr : PC = R[%d]\n",rs);
			break;
		case 0x27:
			ALUresult = ~(ALUinput1 | ALUinput2);
			printf("nor : R[%d] =~(R[%d] | R[%d])\n",rd,rs,rt);
			break;
		case 0x25:
			ALUresult = ALUinput1 | ALUinput2;
			printf("or : R[%d] = R[%d] | R[%d]\n",rd,rs,rt);
			break;
		case 0x2A:
			ALUresult = (ALUinput1 < ALUinput2) ? 1 : 0;
			printf("slt : R[%d] = (R[%d] < R[%d]) ? 1 : 0\n",rd,rs,rt);
			break;
		case 0x2B:
                        ALUresult = (ALUinput1 < ALUinput2) ? 1 : 0;
                        printf("sltu : R[%d] = (R[%d] < R[%d]) ? 1 : 0\n",rd,rs,rt);
			break;
		case 0x0:
			ALUresult = ALUinput2 << shamt;
			printf("sll : R[%d] = R[%d] << %d\n",rd,rs,shamt);
			break;
		case 0x2:
			ALUresult = ALUinput2 >> shamt;
			printf("srl : R[%d] + R[%d] >> %d\n",rd,rs,shamt);
			break;
		case 0x22:
			ALUresult = ALUinput1 - ALUinput2;
			printf("sub : R[%d] = R[%d] - R[%d]\n",rd,rs,rt);
			break;
		case 0x23:
			ALUresult = ALUinput1 - ALUinput2;
			printf("subu : R[%d] = R[%d] - R[%d]\n",rd,rs,rt);
			break;
		}
	}
	else if(format == 'I'){
		switch(opcode){
		case 0x8:
			ALUresult = ALUinput1 + ALUinput2;
			printf("addi : R[%d] = R[%d] + %d\n",rt,rs,SignExtImm);
			break;
		case 0x9:
			ALUresult = ALUinput1 + ALUinput2;
			printf("addi : R[%d] = R[%d] + %d\n",rt,rs,SignExtImm);
			break;
		case 0xC:
			ALUresult = ALUinput1 & ZeroExtImm;
			printf("andi : R[%d] = R[%d] & %d\n",rt,rs,ZeroExtImm);
			break;
		case 0x4:
			break;
		case 0x5:
			break;
		case 0x24:
			ALUresult = M[ALUinput1 + SignExtImm] & 0xFF;
			printf("lbu : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);	
			break;
		case 0x25:
			ALUresult = M[ALUinput1+ SignExtImm] & 0xFFFF;
			printf("lhu : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);
			break;
		case 0x30:
			ALUresult = ALUinput1 + SignExtImm;
			printf("ll : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);
			break;
		case 0x23:
			ALUresult = ALUinput1 + SignExtImm;
                        printf("lw : R[%d] = M[R[%d] + %d]\n",rt,rs,SignExtImm);
			break;
		case 0xD:
			ALUresult = ALUinput1 | ZeroExtImm;
			printf("ori : R[%d] = R[%d] | %d\n",rt,rs,ZeroExtImm);
			break;
		case 0xA:
			ALUresult = (ALUinput1 < SignExtImm)? 1: 0;
			printf("slti : R[%d] = (R[%d] < %d)? 1 : 0\n",rt,rs,SignExtImm);
			break;
		case 0xB:
			ALUresult = (ALUinput1 < SignExtImm)? 1: 0;
			printf("sltiu : R[%d] = (R[%d] < %d)? 1 : 0\n",rt,rs,SignExtImm);
			break;
		case 0xF:
			ALUresult = imm << 16;
			printf("lui : R[%d] = %X << 16\n", rt,imm);
			break;
		default :
			break;
		}
	}
	else{
		switch(opcode){
		case 0x2:
			printf("j : PC = %X\n", JumpAddr);
			PC = JumpAddr;
			break;
		case 0x3:
			Reg[31] = PC + 8;
			PC = JumpAddr;
			printf("jal : R[31] + PC +8, PC = %X",JumpAddr);
			break;
		}	
	   }
if(MemtoReg == 1)
                RwriteD = MreadD;
        else
                RwriteD = ALUresult;

}

void control()
{
	int JumpAddr = PC & 0xF0000000 | address << 2;
        int SignExtImm = SignExt(imm);
        int BranchAddr = SignExtImm << 2;
	int BROut;

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
	if((opcode != 0x2B) && (opcode != 0x4) && (opcode != 0x5) && (opcode != 0x2) && (funct != 0x8))
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
	if((opcode == 0x2) || (opcode == 0x3))
		PCSrc1 = 1;
	else
		PCSrc1 = 0;
	if(((opcode == 0x4) && (Reg[rs] == Reg[rt])) || ((opcode == 0x5) && (Reg[rs] != Reg[rt])))
	{
		PCSrc2 = 1;
		nb++;
	}
	else
		PCSrc2 = 0;	

	RreadR1 = Reg[rs];
	RreadR2 = Reg[rt];

	if(RegDst == 1)
		RwriteR = Reg[rd];
	else
		RwriteR = Reg[rt];


	RreadD1 = RreadR1;
	RreadD2 = RreadR2;
	ALUinput1 = RreadD1;

	if(ALUSrc == 0)
		ALUinput2 = RreadD2;
	else
		ALUinput2 = SignExt(imm);

        if(PCSrc2 == 0)
                BROut = PC + 4;
        else
                BROut = PC + 4 + BranchAddr;
	

	if(PCSrc1 == 0)
		PC = BROut;
			
}

void LStoMem()
{
	int SignExtImm = SignExt(imm);
	if(MemRead == 1)
	{
	Maddress = ALUresult;
	MreadD = M[Maddress];
	}
	switch(opcode){
	case 0x28:
		M[ALUinput1 + SignExtImm] = Reg[rt] & 0xFF;
		break;
	case 0x29:
		M[ALUinput1 + SignExtImm] = Reg[rt] & 0xFFFF;
		break;
	case 0x2B:
		M[ALUinput1 + SignExtImm] = Reg[rt];
		break;
	}

}

void writeback()
{
	if(MemtoReg == 0)
		RwriteD = ALUresult;
	else
		RwriteD = MreadD;
	
	if((format == 'R')&&(funct != 0x8))
		Reg[rd] =RwriteD;
	else if((RegWrite==1)&&(opcode!=0x3))
		Reg[rt] = RwriteD;
	else;

}

int SignExt(int imm)
{
	if (imm >= 0x8000)
		return 0xffff0000 | imm;
	else
		return imm;
}
