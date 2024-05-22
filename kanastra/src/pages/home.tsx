import { useFile } from '@/contexts/contextFile';
import {  FileUploader } from '@/components';
import { format, utcToZonedTime, zonedTimeToUtc } from 'date-fns-tz';

const formatDate = (dateStr: string) => {
  const utcDate = zonedTimeToUtc(dateStr, 'UTC');
  const timeZone = 'America/Sao_Paulo';
  const dateZone = utcToZonedTime(utcDate, timeZone);
  return format(dateZone, 'dd/MM/yyyy HH:mm', { timeZone });
};

const Home = () => {
  const { files } = useFile();

  return (
    <div className="p-5">
      <FileUploader />
      <div className="mt-6 overflow-hidden shadow-lg rounded-lg">
      <div className="relative w-full overflow-auto">
        <div className="text-center items-center justify-center mt-4 text-slate-500 dark:text-slate-400 bg-gray-200 text-lg font-semibold p-6">
        Arquivos recebidos
        </div>
        <table className="w-full caption-bottom text-sm">
          <thead className="bg-gray-100 [&_tr]:border-b">
            <tr className="border-b transition-colors hover:bg-slate-100/50 data-[state=selected]:bg-slate-100 dark:hover:bg-slate-800/50 dark:data-[state=selected]:bg-slate-800">
              <th className="h-12 px-4 text-left align-middle font-medium text-slate-500 [&:has([role=checkbox])]:pr-0 dark:text-slate-400">ID</th>
              <th className="h-12 px-4 text-left align-middle font-medium text-slate-500 [&:has([role=checkbox])]:pr-0 dark:text-slate-400">Nome do arquivo</th>
              <th className="h-12 px-4 text-left align-middle font-medium text-slate-500 [&:has([role=checkbox])]:pr-0 dark:text-slate-400">Data de envio</th>
              <th className="h-12 px-4 text-left align-middle font-medium text-slate-500 [&:has([role=checkbox])]:pr-0 dark:text-slate-400">Linhas</th>
            </tr>
          </thead>
          <tbody className="[&_tr:last-child]:border-0">
            {files.map((file, index) => (
              <tr className="border-b transition-colors hover:bg-slate-100/50 data-[state=selected]:bg-slate-100 dark:hover:bg-slate-800/50 dark:data-[state=selected]:bg-slate-800">
                <td className="p-4 align-middle [&:has([role=checkbox])]:pr-0">{file.id}</td>
                <td className="p-4 align-middle [&:has([role=checkbox])]:pr-0">{file.name}</td>
                <td className="p-4 align-middle [&:has([role=checkbox])]:pr-0">{formatDate(file.created_at)}</td>
                <td className="p-4 align-middle [&:has([role=checkbox])]:pr-0">{file.lines}</td>
              </tr>
            ))}
          </tbody>
        </table>
        </div>
      </div>
    </div>
  );
};

export default Home;
