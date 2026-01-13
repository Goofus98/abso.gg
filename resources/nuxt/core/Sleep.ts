type SleepTrain = () => Promise<void>;
class KillSleepException {}
export async function sleepTrain(f:SleepTrain){
    try {
      await f();
    } catch (e:any){
      if (!(e instanceof KillSleepException)) {
        throw e;
      }
    }
}

export class Killswitch {
    private timeoutId: ReturnType<typeof setTimeout> | null = null;
    private reject: Function|null = null;
    bind(timeoutId: ReturnType<typeof setTimeout>, reject: Function) {
        this.timeoutId = timeoutId;
        this.reject = reject;
    }

    clear() {
        this.timeoutId = null;
        this.reject = null;
    }

    kill() {
        if (this.timeoutId !== null) {
            clearTimeout(this.timeoutId);
            this.reject!(new KillSleepException());
            this.clear();
        }
    }
}

export async function sleep(ms: number, ks: Killswitch|undefined) {
    if (ks != null) {
        return new Promise<void>((resolve, reject) => ks.bind(setTimeout(resolve, ms), reject));
    } else {
        return new Promise<void>(resolve => setTimeout(resolve, ms));
    }
}